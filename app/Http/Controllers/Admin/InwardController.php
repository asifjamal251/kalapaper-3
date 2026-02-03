<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Inward\InwardCollection;
use App\Imports\InwardImport;
use App\Models\DailyStock;
use App\Models\Inward;
use App\Models\InwardItem;
use App\Models\Quality;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InwardController extends Controller{

    public function index(Request $request){
        session()->forget(['rows', 'im_errors', 'status']);
        if ($request->ajax()) {

            if ($request->type === 'quality') {

                return response()->json(
                    Quality::orderBy('name', 'asc')
                    ->get()
                    ->map(function ($quality) {
                        return [
                            'id'   => $quality->id,
                            'text' => $quality->name_with_code, // accessor
                        ];
                    })
                );
            }



            $datas = Inward::withCount('items');

             $orderableColumns = [
                'challan_no'     => 'challan_no',
                'challan_date'   => 'challan_date',
                'e_way_bill_no'  => 'e_way_bill_no',
                'vehicle_no'     => 'vehicle_no',
                'transport'      => 'transport',
                'status'         => 'status_id',
                'items'          => 'items_count',
                'created_at'     => 'created_at',
            ];

            if ($request->has('order')) {
                foreach ($request->order as $order) {
                    $columnIndex = $order['column'];
                    $direction   = $order['dir'] === 'desc' ? 'desc' : 'asc';
                    $columnName  = $request->columns[$columnIndex]['data'];

                    if (isset($orderableColumns[$columnName])) {
                        $datas->orderBy($orderableColumns[$columnName], $direction);
                    }
                }
            } else {
                $datas->orderBy('id', 'desc');
            }

            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new InwardCollection($datas));

        }
        return view('admin.inward.list');
    }

    public function create(Request $request ){
        session()->forget(['rows', 'im_errors', 'status']);
        return view('admin.inward.create');
    }


    public function store(Request $request){
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
            'status' => 'required'
        ]);

        $import = new InwardImport($request->status);
Excel::import($import, $request->file('file'));

session([
    'rows'      => $import->rows,      // valid rows only
    'im_errors' => $import->errors,    // validation errors
    'status'    => $request->status,
]);

        // $import = new InwardImport();
        // Excel::import($import, $request->file('file'));
        // session([
        //     'rows'   => $import->rows,
        //     'im_errors' => $import->errors,
        //     'status' => $request->status,
        // ]);

        return response()->json([
            'class' => 'bg-success',
            'error' => false,
            'message' => 'Check Import Preview Successfully',
            'call_back' => route('admin.inward.preview'),
            'table_refresh' => false,
            'model_id' => 'dataSave'
        ]);
    }

    public function preview(){
        $importErrors = session('im_errors', []);
        $rows = session('rows', []);
        $errorRows = collect($importErrors)->pluck('row')->toArray();
        $status = session('status');

        return view('admin.inward.preview', [
            'rows'         => $rows,
            'importErrors' => $importErrors,
            'errorRows'    => $errorRows,
            'status'       => $status,
        ]);
    }

    public function confirm(){
        $rows = collect(session('rows', []))->where('_is_valid', true);

        if ($rows->isEmpty()) {
            return redirect()->back()->with('error', 'No valid data to import.');
        }


        $duplicateInward = Inward::whereIn('challan_no', $rows->pluck('challan_no'))->whereIn('e_way_bill_no', $rows->pluck('e_way_bill_no'))->first();

        if ($duplicateInward) {
            return redirect()->back()->with([
                'class'   => 'bg-danger',
                'message' => 'Challan number and E-Way Bill already exist in system.'
            ]);
        }

        $duplicateHU = InwardItem::whereIn('handling_unit', $rows->pluck('handling_unit'))->first();

        if ($duplicateHU) {
            return redirect()->back()->with([
                'class'   => 'danger',
                'message' => 'One or more Handling Units already exist in system.'
            ]);
        }

        DB::transaction(function () use ($rows) {
            $status = session('status');
            $stock  = DailyStock::today();

            $grouped = $rows->groupBy(function ($row) {
                return implode('|', [
                    $row['challan_no'],
                    $row['e_way_bill_no'],
                    $row['vehicle_no'],
                    $row['challan_date'],
                ]);
            });

            foreach ($grouped as $items) {

                $first = $items->first();
                $inward = Inward::updateOrCreate(
                    [
                        'challan_no'    => $first['challan_no'],
                        'e_way_bill_no' => $first['e_way_bill_no'],
                        'challan_date' => $first['challan_date'],
                    ],
                    [
                        'challan_from' => $first['challan_from'],
                        'vehicle_no'   => $first['vehicle_no'],
                        'transport'    => $first['transport'],
                        'status_id'    => $status,
                        'created_by'   => auth('admin')->user()->id,
                    ]
                );

                foreach ($items as $item) {
                    InwardItem::updateOrCreate(
                        [
                            'inward_id'     => $inward->id,
                            'handling_unit' => $item['handling_unit'],
                        ],
                        [
                            'quality_id'       => $item['quality_id'],
                            'job_card_id'      => null,
                            'job_card_item_id' => null,
                            'gsm'              => $item['gsm'],
                            'width'            => $item['width'],
                            'allocation'       => $item['allocation'] ?? null,
                            'weight'           => $item['weight'],
                            'core_dia'         => $item['core_dia'],
                            'reel_dia'         => $item['reel_dia'],
                            'batch'            => $item['batch'],
                            'status_id'        => $status == 21 ? 22 : $status,
                            'stock_date'       => $status == 21 ? now()->toDateString() : null,
                            'booked_at'        => null,
                        ]
                    );

                    // 🔥 DAILY STOCK UPDATE (ONLY PLACE)
                    $weight = (float) $item['weight'];

                    // STATUS 20 → ON WAY
                    if ($status == 20) {
                        $stock->increment('inward_onway', $weight);
                        $stock->increment('available_onway_stock', $weight);
                    }

                    // STATUS 21 → RECEIVED
                    if ($status == 21) {
                        $stock->increment('inward_received', $weight);
                        //$stock->increment('inward_move_to_stock', $weight);
                        $stock->increment('available_stock', $weight);
                    }

                }
            }
        });

        session()->forget(['rows', 'im_errors', 'status']);

        return redirect()->route('admin.inward.index')->with(['class' => 'success', 'message' => 'Inward Import Successfully.']);
    }

    public function show($id){
        $inward = Inward::findOrFail($id);
        return view('admin.inward.view', compact('inward'));
    }

}
