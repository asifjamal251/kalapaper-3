<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InwardItem;
use App\Models\JobCard;
use App\Models\JobCardItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobCardController extends Controller{
    public function index(Request $request){
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
            'job_card_type'  => 'nullable',

            'kt_docs_repeater_advanced'                       => 'required|array|min:1',
            'kt_docs_repeater_advanced.*.inward_item_id'      => 'required|exists:inward_items,id',
            'kt_docs_repeater_advanced.*.purchase_order_item' => 'required|exists:purchase_order_items,id',
            'kt_docs_repeater_advanced.*.gsm'                 => 'required|numeric',
            'kt_docs_repeater_advanced.*.quality'             => 'required|exists:qualities,id',
            'kt_docs_repeater_advanced.*.width'               => 'required|numeric',
        ]);

        try {
            DB::transaction(function () use ($validated, $request) {

                $purchaseOrder = PurchaseOrder::where('id', $validated['purchase_order'])
                    ->whereIn('status_id', [1, 18])
                    ->firstOrFail();

                $jobCard = JobCard::create([
                    'created_by'        => auth()->id(),
                    'purchase_order_id' => $purchaseOrder->id,
                    'ship_to'           => $validated['ship_to'],
                    'sold_to'           => $validated['sold_to'],
                    'reference_number'  => $request->reference_number,
                    'type'              => $validated['type'],
                    'job_card_type'     => $validated['job_card_type'] ?? 'Normal',
                    'status_id'         => 1,
                ]);

                foreach ($validated['kt_docs_repeater_advanced'] as $index => $item) {

                    $poItem = PurchaseOrderItem::where('id', $item['purchase_order_item'])
                        ->where('purchase_order_id', $purchaseOrder->id)
                        ->first();

                    if (
                        !$poItem ||
                        (float) $poItem->gsm !== (float) $item['gsm'] ||
                        (int) $poItem->quality_id !== (int) $item['quality'] ||
                        round($poItem->width, 2) !== round($item['width'], 2)
                    ) {
                        throw new \Exception('Row '.($index + 1).' data mismatch with Purchase Order Item');
                    }

                    JobCardItem::create([
                        'job_card_id'            => $jobCard->id,
                        'inward_item_id'         => $item['inward_item_id'],
                        'purchase_order_item_id' => $poItem->id,
                        'wastage_id'             => $item['wastage'] ?? null,
                        'item_no'                => $item['item_number'] ?? null,
                        'with_cm'                => $item['width'],
                        'length_cm'              => $item['length_cm'] ?? null,
                        'length_inch'            => $item['length_inch'] ?? null,
                        'trim'                   => $item['trim'] ?? 0,
                        'bundle_pack'            => $item['bundle_pack'] ?? 0,
                        'ream_weight'            => $item['ream_weight'] ?? 0,
                        'sheet_per_ream'         => $item['sheet_per_ream'] ?? 0,
                        'run_number'             => $item['run_number'] ?? 0,
                        'status_id'              => 1,
                    ]);
                }
            });

            return response()->json([
                'class' => 'bg-success',
                'error' => false,
                'message' => 'Job Card saved successfully',
                'call_back' => route('admin.'.request()->segment(2).'.index'),
                'table_refresh' => true,
                'model_id' => 'dataSave'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'class' => 'bg-danger',
                'error' => true,
                'message' => $e->getMessage(),
                'call_back' => '',
                'table_refresh' => false,
                'model_id' => ''
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
