<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PurchaseOrderExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PurchaseOrder\PurchaseOrderCollection;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;


class PurchaseOrderController extends Controller{
    public function index(Request $request){

        if ($request->wantsJson()) {
            $datas = PurchaseOrder::orderByRaw("CASE
                WHEN status_id = 1 THEN 1
                WHEN status_id = 2 THEN 2
                WHEN status_id = 3 THEN 3
                ELSE 4
                END")->orderBy('id', 'desc');

            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new PurchaseOrderCollection($datas));
        }
        return view('admin.purchase-order.list'); 
    }



    public function create(){
         return view('admin.purchase-order.create');
    }




    public function store(Request $request){
        $validated = $request->validate(
            [
                'from'      => ['required', 'exists:parties,id'],
                'bill_to'   => ['required', 'exists:parties,id'],
                'ship_to'   => ['required', 'exists:parties,id'],
                'consignee' => ['nullable', 'exists:parties,id'],
                'po_date'   => ['required'],

                'kt_docs_repeater_advanced' => ['required', 'array', 'min:1'],

                'kt_docs_repeater_advanced.*.quality' => ['required', 'exists:qualities,id'],
                'kt_docs_repeater_advanced.*.gsm' => ['required'],
                'kt_docs_repeater_advanced.*.type'    => ['required', Rule::in(['Reel', 'Sheet'])],
                'kt_docs_repeater_advanced.*.grain'   => ['required', Rule::in(['Long', 'Short'])],
                'kt_docs_repeater_advanced.*.length'  => ['required', 'numeric', 'min:0'],
                'kt_docs_repeater_advanced.*.width'   => ['required', 'numeric', 'min:0'],
                'kt_docs_repeater_advanced.*.ream_weight' => ['nullable', 'numeric', 'min:0'],
                'kt_docs_repeater_advanced.*.quantity'    => ['required', 'numeric', 'min:0.001'],
                'kt_docs_repeater_advanced.*.discount'    => ['nullable', 'numeric', 'min:0'],
                'kt_docs_repeater_advanced.*.remarks'     => ['nullable', 'string', 'max:255'],
                'kt_docs_repeater_advanced.*.sold_to'     => ['required', 'exists:parties,id'],
            ],[
                // Parties
                'from.required'      => 'From party is required.',
                'from.exists'        => 'Selected From party is invalid.',

                'bill_to.required'  => 'Bill To party is required.',
                'bill_to.exists'    => 'Selected Bill To party is invalid.',

                'ship_to.required'  => 'Ship To party is required.',
                'ship_to.exists'    => 'Selected Ship To party is invalid.',

                'consignee.exists'  => 'Selected Consignee is invalid.',

                'po_date.required'  => 'PO date is required.',

                // Repeater
                'kt_docs_repeater_advanced.required' => 'Please add at least one item.',
                'kt_docs_repeater_advanced.array'    => 'Invalid item format.',
                'kt_docs_repeater_advanced.min'      => 'At least one item is required.',

                // Item fields
                'kt_docs_repeater_advanced.*.gsm.required' => 'GSM is required.',

                'kt_docs_repeater_advanced.*.quality.required' => 'Quality is required.',
                'kt_docs_repeater_advanced.*.quality.exists'   => 'Selected quality is invalid.',

                'kt_docs_repeater_advanced.*.type.required' => 'Type is required.',
                'kt_docs_repeater_advanced.*.type.in'       => 'Type must be Reel or Sheet.',

                'kt_docs_repeater_advanced.*.grain.required' => 'Grain direction is required.',
                'kt_docs_repeater_advanced.*.grain.in'       => 'Grain must be Long or Short.',

                'kt_docs_repeater_advanced.*.length.required' => 'Length is required.',
                'kt_docs_repeater_advanced.*.length.numeric'  => 'Length must be a number.',
                'kt_docs_repeater_advanced.*.length.min'      => 'Length must be zero or greater.',

                'kt_docs_repeater_advanced.*.width.required' => 'Width is required.',
                'kt_docs_repeater_advanced.*.width.numeric'  => 'Width must be a number.',
                'kt_docs_repeater_advanced.*.width.min'      => 'Width must be zero or greater.',

                'kt_docs_repeater_advanced.*.ream_weight.numeric' => 'Ream weight must be a number.',
                'kt_docs_repeater_advanced.*.ream_weight.min'     => 'Ream weight must be zero or greater.',

                'kt_docs_repeater_advanced.*.quantity.required' => 'Quantity is required.',
                'kt_docs_repeater_advanced.*.quantity.numeric'  => 'Quantity must be a number.',
                'kt_docs_repeater_advanced.*.quantity.min'      => 'Quantity must be at least 1.',

                'kt_docs_repeater_advanced.*.discount.numeric' => 'Discount must be a number.',
                'kt_docs_repeater_advanced.*.discount.min'     => 'Discount must be zero or greater.',

                'kt_docs_repeater_advanced.*.remarks.string' => 'Remarks must be text only.',
                'kt_docs_repeater_advanced.*.remarks.max'    => 'Remarks may not exceed 255 characters.',

                'kt_docs_repeater_advanced.*.sold_to.required' => 'Sold To party is required.',
                'kt_docs_repeater_advanced.*.sold_to.exists'   => 'Selected Sold To party is invalid.',
            ]
        );

        try {
            DB::transaction(function () use ($validated) {

                $po = PurchaseOrder::create([
                    'created_by'=> auth('admin')->user()->id,
                    'from'      => $validated['from'],
                    'bill_to'   => $validated['bill_to'],
                    'ship_to'   => $validated['ship_to'],
                    'consignee' => $validated['consignee'] ?? null,
                    'po_date'   => Carbon::parse($validated['po_date'])->format('Y-m-d'),
                    'status_id' => 1,
                ]);

                foreach ($validated['kt_docs_repeater_advanced'] as $item) {
                    PurchaseOrderItem::create([
                        'purchase_order_id'  => $po->id,
                        'sold_to'            => $item['sold_to'],
                        'quality_id'         => $item['quality'],
                        'gsm'                => $item['gsm'],
                        'type'               => $item['type'],
                        'grain'              => $item['grain'],
                        
                        'length_cm'   => $item['length'],
                        'length_inch' => cmToStandardInch($item['length']),

                        'width_cm'    => $item['width'],
                        'width_inch'  => cmToStandardInch($item['width']),

                        'item_number'        => $item['item_number'] ?? 0,
                        'ream_weight'        => $item['ream_weight'] ?? 0,
                        'quantity'           => $item['quantity'],
                        'quantity_kg'        => $item['quantity'] * 1000,
                        'discount'           => $item['discount'] ?? 0,
                        'remarks'            => $item['remarks'] ?? null,
                        'job_card_weight'    => 0,
                        'status_id'          => 1,
                    ]);
                }
            });

            return response()->json([
                'class'         => 'bg-success',
                'error'         => false,
                'message'       => 'PO Saved Successfully',
                'call_back'     => route('admin.' . request()->segment(2) . '.index'),
                'table_refresh' => true,
                'model_id'      => null
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'class'         => 'bg-danger',
                'error'         => true,
                'message'       => $e->getMessage(),
                'call_back'     => null,
                'table_refresh' => false,
                'model_id'      => null
            ], 500);
        }
    }


    public function edit($id){
        $purchase_order = PurchaseOrder::findOrFail($id);
        return view('admin.purchase-order.edit', compact('purchase_order'));
    }


    public function update(Request $request, $id){
    $validated = $request->validate([
        'from'      => ['required','exists:parties,id'],
        'bill_to'   => ['required','exists:parties,id'],
        'ship_to'   => ['required','exists:parties,id'],
        'consignee' => ['nullable','exists:parties,id'],
        'po_date'   => ['required'],
        'kt_docs_repeater_advanced' => ['required','array','min:1'],
        'kt_docs_repeater_advanced.*.quality' => ['required','exists:qualities,id'],
        'kt_docs_repeater_advanced.*.gsm' => ['required'],
        'kt_docs_repeater_advanced.*.type' => ['required',Rule::in(['Reel','Sheet'])],
        'kt_docs_repeater_advanced.*.grain' => ['required',Rule::in(['Long','Short'])],
        'kt_docs_repeater_advanced.*.length' => ['required','numeric','min:0'],
        'kt_docs_repeater_advanced.*.width' => ['required','numeric','min:0'],
        'kt_docs_repeater_advanced.*.ream_weight' => ['nullable','numeric','min:0'],
        'kt_docs_repeater_advanced.*.quantity' => ['required','numeric','min:0.001'],
        'kt_docs_repeater_advanced.*.discount' => ['nullable','numeric','min:0'],
        'kt_docs_repeater_advanced.*.remarks' => ['nullable','string','max:255'],
        'kt_docs_repeater_advanced.*.sold_to' => ['required','exists:parties,id'],
    ]);

    try {

        DB::transaction(function () use ($validated, $id) {

            $po = PurchaseOrder::findOrFail($id);

            $po->update([
                'from'      => $validated['from'],
                'bill_to'   => $validated['bill_to'],
                'ship_to'   => $validated['ship_to'],
                'consignee' => $validated['consignee'] ?? null,
                'po_date'   => Carbon::parse($validated['po_date'])->format('Y-m-d'),
            ]);

            $existingIds = [];

            foreach ($validated['kt_docs_repeater_advanced'] as $item) {

                $data = [
                    'purchase_order_id' => $po->id,
                    'sold_to'           => $item['sold_to'],
                    'quality_id'        => $item['quality'],
                    'gsm'               => $item['gsm'],
                    'type'              => $item['type'],
                    'grain'             => $item['grain'],
                    'length_cm'         => $item['length'],
                    'length_inch'       => cmToStandardInch($item['length']),
                    'width_cm'          => $item['width'],
                    'width_inch'        => cmToStandardInch($item['width']),
                    'item_number'       => $item['item_number'] ?? 0,
                    'ream_weight'       => $item['ream_weight'] ?? 0,
                    'quantity'          => $item['quantity'],
                    'quantity_kg'       => $item['quantity'] * 1000,
                    'discount'          => $item['discount'] ?? 0,
                    'remarks'           => $item['remarks'] ?? null,
                ];

                if (!empty($item['id'])) {

                    $poItem = PurchaseOrderItem::where('id',$item['id'])
                        ->where('purchase_order_id',$po->id)
                        ->firstOrFail();

                    $poItem->update($data);

                    $existingIds[] = $poItem->id;

                } else {

                    $newItem = PurchaseOrderItem::create($data + [
                        'job_card_weight'=>0,
                        'status_id'=>1
                    ]);

                    $existingIds[] = $newItem->id;
                }
            }

   
            if (!empty($existingIds)) {
                PurchaseOrderItem::where('purchase_order_id', $po->id)
                    ->where('status_id', 1)
                    ->whereNotIn('id', $existingIds)
                    ->delete();
            }
        });

        return response()->json([
            'class'=>'bg-success',
            'error'=>false,
            'message'=>'PO Updated Successfully',
            'call_back'=>route('admin.'.request()->segment(2).'.index'),
            'table_refresh'=>true
        ]);

    } catch (\Throwable $e) {

        return response()->json([
            'class'=>'bg-danger',
            'error'=>true,
            'message'=>$e->getMessage()
        ],500);
    }
}


    public function show($id){
        $purchase_order = PurchaseOrder::findOrFail($id);
        return view('admin.purchase-order.view', compact('purchase_order'));
    }

    

    public function downloadExcel(Request $request, $id){
        $purchase_order = PurchaseOrder::findOrFail($id);
        $poNumber = Str::of($purchase_order->po_number)
            ->replace(['/', '\\'], '-') 
            ->replace(' ', '_')        
            ->lower();

        $fileName = "purchase_order_{$poNumber}.xlsx";

        return Excel::download(
            new PurchaseOrderExport($purchase_order),
            $fileName
        );
    }



}
