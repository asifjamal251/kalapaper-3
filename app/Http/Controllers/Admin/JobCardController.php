<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\JobCard\JobCardCollection;
use App\Models\InwardItem;
use App\Models\JobCard;
use App\Models\JobCardItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Services\DailyStockService;
use App\Services\StockLedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobCardController extends Controller{
    public function index(Request $request){
        session()->forget(['rows', 'im_errors', 'status']);
        if ($request->ajax()) {
            $datas = JobCard::withCount('items');
            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();
            return response()->json(new JobCardCollection($datas));
        }
        return view('admin.job-card.list');
    }

    public function create(Request $request){
        $jobCardItemsSession = session()->get('job_card_item', []);
        $jobCardItemIds = collect($jobCardItemsSession)->pluck('job_card_item_id')->toArray();
        $inward_items = InwardItem::whereIn('id', $jobCardItemIds)->get();
        if($inward_items->count() > 0){
            return view('admin.job-card.create', compact('inward_items'));
        }
        return redirect()->route('admin.job-card.index');
    }



    public function store(Request $request){
    $validated = $request->validate([
        'purchase_order' => 'required|exists:purchase_orders,id',
        'ship_to'        => 'required|exists:parties,id',
        'sold_to'        => 'required|exists:parties,id',
        'type'           => 'required|in:Reel,Sheet',
        'kt_docs_repeater_advanced' => 'required|array|min:1',
        'kt_docs_repeater_advanced.*.purchase_order_item' => 'required|exists:purchase_order_items,id',
        'kt_docs_repeater_advanced.*.inward_item_id'      => 'nullable|exists:inward_items,id',
        'kt_docs_repeater_advanced.*.item_number'   => 'nullable|integer',
        'kt_docs_repeater_advanced.*.gsm'           => 'required|numeric',
        'kt_docs_repeater_advanced.*.quality'       => 'required|exists:qualities,id',
        'kt_docs_repeater_advanced.*.width_cm'      => 'required|numeric',
        'kt_docs_repeater_advanced.*.length_cm'     => 'required|numeric',
        'kt_docs_repeater_advanced.*.wastage'       => 'required|exists:wastages,id',
        'kt_docs_repeater_advanced.*.ream_weight'   => 'required|numeric',
        'kt_docs_repeater_advanced.*.bundle_pack'   => 'nullable|string',
        'kt_docs_repeater_advanced.*.sheet_per_ream'=> 'nullable|string',
        'kt_docs_repeater_advanced.*.run_number'    => 'nullable|string',
    ]);

    try {

        DB::transaction(function () use ($validated) {

            $purchaseOrder = PurchaseOrder::where('id', $validated['purchase_order'])
                ->whereIn('status_id', [1,18])
                ->firstOrFail();

            $inwardIds = collect($validated['kt_docs_repeater_advanced'])
                ->pluck('inward_item_id')
                ->filter()
                ->unique()
                ->values();

            $inwardItems = InwardItem::availableForJobCard()
                ->whereIn('id', $inwardIds)
                ->get()
                ->keyBy('id');

            $jobCardType = collect($validated['kt_docs_repeater_advanced'])
                ->contains(function ($item) use ($inwardItems) {
                    $inward = !empty($item['inward_item_id'])
                        ? ($inwardItems[$item['inward_item_id']] ?? null)
                        : null;

                    return $inward && $inward->parent_inward_item_id !== null;
                }) ? 'Jumbo' : 'Normal';

            $jobCard = JobCard::create([
                'created_by'        => auth('admin')->id(),
                'from'              => $purchaseOrder->from,
                'purchase_order_id' => $purchaseOrder->id,
                'ship_to'           => $validated['ship_to'],
                'sold_to'           => $validated['sold_to'],
                'type'              => $validated['type'],
                'job_card_type'     => $jobCardType,
                'status_id'         => 1,
            ]);

            $parentGroups = [];
            $hasParent = false;
            $hasNoParent = false;

            foreach ($validated['kt_docs_repeater_advanced'] as $index => $item) {

                if (!empty($item['inward_item_id'])) {

                    $inwardItem = $inwardItems[$item['inward_item_id']] ?? null;

                    if (!$inwardItem) {
                        throw new \Exception('Row '.($index + 1).' : Inward Item invalid or already used');
                    }

                    $parentId = $inwardItem->parent_inward_item_id;

                    if ($parentId === null) {
                        $hasNoParent = true;
                    } else {
                        $hasParent = true;
                        $parentGroups[$parentId][] = $inwardItem->id;
                    }
                }
            }

            if ($hasParent && $hasNoParent) {
                throw new \Exception('All items must be same type. Either Jumbo Or Normal.');
            }

            foreach ($parentGroups as $parentId => $selectedChildren) {

                $allChildren = InwardItem::where('parent_inward_item_id', $parentId)
                    ->pluck('id')
                    ->toArray();

                sort($selectedChildren);
                sort($allChildren);

                if ($selectedChildren !== $allChildren) {
                    throw new \Exception("All child inward items of parent ID {$parentId} must be added.");
                }
            }

            $totalWeight = 0;
            $totalInwardReduced = 0;

            foreach ($validated['kt_docs_repeater_advanced'] as $index => $item) {

                $poItem = PurchaseOrderItem::where('id', $item['purchase_order_item'])
                    ->where('purchase_order_id', $purchaseOrder->id)
                    ->whereIn('status_id', [1,18])
                    ->first();

                if (!$poItem) {
                    throw new \Exception('Row '.($index + 1).' : Purchase Order Item not found or invalid status');
                }

                $inwardItem = !empty($item['inward_item_id'])
                    ? ($inwardItems[$item['inward_item_id']] ?? null)
                    : null;

                if (!empty($item['inward_item_id']) && !$inwardItem) {
                    throw new \Exception('Row '.($index + 1).' : Inward Item invalid or already used');
                }

                $rowErrors = [];

                if ((float)$poItem->gsm !== (float)$item['gsm']) {
                    $rowErrors[] = 'GSM mismatch';
                }

                if ((int)$poItem->quality_id !== (int)$item['quality']) {
                    $rowErrors[] = 'Quality mismatch';
                }

                if (round($poItem->width_cm,2) !== round($item['width_cm'],2)) {
                    $rowErrors[] = 'Width mismatch';
                }

                if ((int)$poItem->sold_to !== (int)$validated['sold_to']) {
                    $rowErrors[] = 'Sold To mismatch';
                }

                if (!empty($rowErrors)) {
                    throw new \Exception('Row '.($index + 1).' error: '.implode(', ', $rowErrors));
                }

                $itemWeight = $inwardItem ? (float)$inwardItem->weight : 0;

                $currentUsedWeight = (float)$poItem->job_card_weight;
                $maxAllowedWeight  = (float)$poItem->quantity_kg;
                $maxAllowedWithTolerance = $maxAllowedWeight * 1.10;
                $totalAfterAdd = $currentUsedWeight + $itemWeight;

                if (round($totalAfterAdd,2) > round($maxAllowedWithTolerance,2)) {
                    throw new \Exception('Row '.($index + 1).' : Weight exceeds allowed limit (10% tolerance applied)');
                }

                $totalWeight += $itemWeight;
                $totalInwardReduced += $itemWeight;

                $jobCardItem = $jobCard->items()->create([
                    'inward_item_id' => $inwardItem->id ?? null,
                    'quality_id'     => $inwardItem->quality_id ?? $item['quality'],
                    'wastage_id'     => $item['wastage'],
                    'item_number'    => $item['item_number'] ?? null,
                    'width_cm'       => $item['width_cm'],
                    'width_inch'     => cmToStandardInch($item['width_cm']),
                    'length_cm'      => $item['length_cm'],
                    'length_inch'    => cmToStandardInch($item['length_cm']),
                    'gsm'            => $item['gsm'],
                    'weight'         => $itemWeight,
                    'handling_unit'  => $inwardItem->handling_unit ?? null,
                    'trim'           => $item['trim'] ?? 0,
                    'bundle_pack'    => $item['bundle_pack'] ?? null,
                    'sheet_per_ream' => $item['sheet_per_ream'] ?? null,
                    'run_number'     => $index + 1,
                ]);

                $newStatus = (round($totalAfterAdd,2) >= round($maxAllowedWeight,2)) ? 3 : 18;

                $poItem->update([
                    'job_card_weight' => $totalAfterAdd,
                    'status_id'       => $newStatus,
                ]);

                if ($inwardItem) {
                    $inwardItem->update([
                        'status_id' => 23,
                        'job_card_id' => $jobCard->id,
                        'job_card_item_id' => $jobCardItem->id,
                        'booked_at' => now()
                    ]);
                }
            }

            $jobCard->update([
                'total_weight' => round($totalWeight,2),
                'ready_weight' => 0,
                'delivered_weight' => 0,
                'excess' => 0,
            ]);

            // if($totalInwardReduced > 0){
            //     DailyStockService::reduceInwardStock($totalInwardReduced);
            // }

            DailyStockService::updateJobCardBooking($totalWeight);

            StockLedgerService::add([
                'source_type' => 'JobCard',
                'source_id'   => $jobCard->id,
                'type'        => 'out',
                'new_stock'   => $totalWeight
            ]);
        });

        if (session()->has('job_card_item')) {
            session()->forget('job_card_item');
        }

        return response()->json([
            'class'         => 'bg-success',
            'error'         => false,
            'message'       => 'Job Card saved successfully',
            'call_back'     => route('admin.'.request()->segment(2).'.index'),
            'table_refresh' => true,
            'model_id'      => 'dataSave'
        ]);

    } catch (\Throwable $e) {

        return response()->json([
            'class'         => 'bg-danger',
            'error'         => true,
            'message'       => $e->getMessage(),
            'table_refresh' => false
        ], 422);
    }
}




public function removeFromSession(Request $request){
    $request->validate([
        'id' => 'required|integer',
    ]);

    $jobCard = session()->get('job_card_item', []);
    unset($jobCard[$request->id]);
    session()->put('job_card_item', $jobCard);

    return response()->json([
        'success' => true,
        'message' => 'Removed from session',
        'count' => count($jobCard),
    ]);
}
}
