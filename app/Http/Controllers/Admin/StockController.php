<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Stock\StockCollection;
use App\Imports\InwardImport;
use App\Models\DailyStock;
use App\Models\Inward;
use App\Models\InwardItem;
use App\Models\Quality;
use App\Services\HandlingUnitService;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class StockController extends Controller{

    public function index(Request $request){
        if ($request->ajax()) {
            $jobCardItem = session('job_card_item'); 

            $datas = InwardItem::with(['quality', 'inward', 'jobCard']);

            if (!empty($jobCardItem)) {
                $jobCardItemIds = array_column($jobCardItem, 'job_card_item_id');
                $datas->orderByRaw("FIELD(id, " . implode(',', $jobCardItemIds) . ") DESC");
            }

            


            $datas
                ->when($request->filled('quality'), fn ($q) =>
                    $q->where('quality_id', $request->quality)
                )
                ->when($request->filled('gsm'), fn ($q) =>
                    $q->where('gsm', 'LIKE', '%' . $request->gsm . '%')
                )
                ->when($request->filled('width'), fn ($q) =>
                    $q->where('width', 'LIKE', '%' . $request->width . '%')
                )
                ->when($request->filled('handling_unit'), fn ($q) =>
                    $q->where('handling_unit', 'LIKE', '%' . $request->handling_unit . '%')
                )
                ->when($request->filled('status'), fn ($q) =>
                    $q->where('status_id', $request->status)
                );


            $datas
                ->when($request->filled('challan_no'), function ($q) use ($request) {
                    $q->whereHas('inward', function ($sub) use ($request) {
                        $sub->where('challan_no', 'LIKE', '%' . $request->challan_no . '%');
                    });
                })
                ->when($request->filled('challan_date'), function ($q) use ($request) {
                    $date = Carbon::parse($request->challan_date)->format('Y-m-d');

                    $q->whereHas('inward', function ($sub) use ($date) {
                        $sub->whereDate('challan_date', $date);
                    });
                });


            $datas->when($request->filled('job_card'), function ($q) use ($request) {
                $q->whereHas('jobCard', function ($sub) use ($request) {
                    $sub->where('job_card_number', 'LIKE', '%' . $request->job_card . '%');
                });
            });


            $datas->when($request->filled('booked_date'), function ($q) use ($request) {
                $q->whereDate('booked_at', $request->booked_date);
            });

             $orderableColumns = [
                'gsm'            => 'gsm',
                'width'          => 'width',
                'weight'         => 'weight',
                'stock_date'     => 'stock_date',
                'quality'        => 'quality',
            ];

            if ($request->has('order')) {
                foreach ($request->order as $order) {

                    $columnIndex = $order['column'];
                    $direction   = $order['dir'] === 'desc' ? 'desc' : 'asc';
                    $columnName  = $request->columns[$columnIndex]['data'];

                    if ($columnName === 'quality') {
                        $datas->orderBy(
                            Quality::select('name')
                                ->whereColumn('qualities.id', 'inward_items.quality_id'),
                            $direction
                        );
                    } elseif (isset($orderableColumns[$columnName])) {
                        $datas->orderBy($orderableColumns[$columnName], $direction);
                    }
                }
            } else {
                $datas->orderBy('id', 'desc');
            }

            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new StockCollection($datas));

        }
        return view('admin.stock.list');
    }

    public function edit($id){
        $reel = InwardItem::findOrFail($id);
        return view('admin.stock.create', compact('reel'));
    }

    public function update(Request $request, $jumboId){
        $request->validate([
            'width_a' => 'required|numeric|min:0.1',
        ]);

        DB::transaction(function () use ($request, $jumboId) {

            $jumbo = InwardItem::lockForUpdate()->findOrFail($jumboId);

            if ($jumbo->status_id == 25) {
                throw new \Exception('Jumbo already split');
            }

            $totalWidth  = (float) $jumbo->width;
            $totalWeight = (float) $jumbo->weight;
            $widthA      = (float) $request->width_a;

            if ($widthA <= 0 || $widthA >= $totalWidth) {
                throw new \Exception('Invalid split width');
            }


            $widthB  = $totalWidth - $widthA;

            $weightA = round(($widthA / $totalWidth) * $totalWeight, 3);
            $weightB = round($totalWeight - $weightA, 3);

            if (round($weightA + $weightB, 3) !== round($totalWeight, 3)) {
                throw new \Exception('Weight calculation mismatch');
            }

            // ================= SPLIT A =================
            InwardItem::create([
                'inward_id'             => $jumbo->inward_id,
                'quality_id'            => $jumbo->quality_id,
                'parent_inward_item_id' => $jumbo->id,
                'gsm'                   => $jumbo->gsm,
                'width'                 => $widthA,
                'allocation'            => 'Split',
                'weight'                => $weightA,
                'core_dia'              => $jumbo->core_dia,
                'reel_dia'              => $jumbo->reel_dia,
                'batch'                 => $jumbo->batch,
                'stock_date'            => $jumbo->stock_date,
                'handling_unit'         => HandlingUnitService::generateSplit($jumbo->handling_unit),
                'status_id'             => 22,
            ]);

            // ================= SPLIT B =================
            InwardItem::create([
                'inward_id'             => $jumbo->inward_id,
                'quality_id'            => $jumbo->quality_id,
                'parent_inward_item_id' => $jumbo->id,
                'gsm'                   => $jumbo->gsm,
                'width'                 => $widthB,
                'allocation'            => 'Split',
                'weight'                => $weightB,
                'core_dia'              => $jumbo->core_dia,
                'reel_dia'              => $jumbo->reel_dia,
                'batch'                 => $jumbo->batch,
                'stock_date'            => $jumbo->stock_date,
                'handling_unit'         => HandlingUnitService::generateSplit($jumbo->handling_unit),
                'status_id'             => 22,
            ]);

            // ================= DISABLE PARENT =================
            $jumbo->update([
                'status_id' => 25, // Split
            ]);
        });

        return back()->with('success', 'Jumbo reel split successfully');
    }

    




  

    public function splitCancel($id){
        try {
            return DB::transaction(function () use ($id) {

                $stock = InwardItem::where('id', $id)
                    ->where('status_id', 25)
                    ->first();

                if (!$stock) {
                    return response()->json([
                        'message' => 'This reel is not available for cancel.',
                        'class'   => 'bg-warning',
                        'added'   => false,
                    ]);
                }

                $invalidChildExists = InwardItem::where('parent_inward_item_id', $stock->id)
                    ->where('status_id', '!=', 22)
                    ->exists();

                if ($invalidChildExists) {
                    return response()->json([
                        'message' => 'Some split reels are already processed.',
                        'class'   => 'bg-warning',
                        'added'   => false,
                    ]);
                }

                InwardItem::where('parent_inward_item_id', $stock->id)->delete();

                $stock->update([
                    'status_id' => 22
                ]);

                return response()->json([
                    'message' => 'Split removed successfully.',
                    'class'   => 'bg-success',
                    'error'   => false,
                ]);
            });

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong while cancelling split.',
                'class'   => 'bg-danger',
                'error'   => true,
            ], 500);
        }
    }



    public function store(Request $request){
        $inward_item = InwardItem::where('id', $request->id)
            ->where('status_id', 22)
            ->first();

        if (!$inward_item) {
            return response()->json([
                'message' => 'This reel is not available for Process.',
                'class'   => 'bg-warning',
                'added'   => false,
                'count'   => count(session('job_card_item', [])),
            ]);
        }

        $jobCard = session()->get('job_card_item', []);

        if (isset($jobCard[$request->id])) {
            unset($jobCard[$request->id]);
            session()->put('job_card_item', $jobCard);

            return response()->json([
                'message' => 'Removed from Processing Successfully',
                'class'   => 'bg-warning',
                'added'   => false,
                'count'   => count($jobCard),
            ]);
        }

        /* ================= ADD ================= */
        $jobCard[$request->id] = [
            'job_card_item_id' => $inward_item->id,
            'user_id'          => auth('admin')->id(),
        ];

        session()->put('job_card_item', $jobCard);

        return response()->json([
            'message' => 'Added to Processing Successfully',
            'class'   => 'bg-success',
            'added'   => true,
            'count'   => count($jobCard),
        ]);
    }



    public function createBook(Request $request){
        return view('admin.stock.create-book');
    }
}
