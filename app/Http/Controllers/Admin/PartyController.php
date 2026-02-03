<?php

namespace App\Http\Controllers\Admin;

use App\Exports\partiesaleLedgerExport;
use App\Exports\partiesExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Party\PartyCollection;
use App\Models\Party;
use App\Models\ProductLedger;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash;

use App\Imports\PartyImport;
use Maatwebsite\Excel\Facades\Excel;

class PartyController extends Controller{

    public function index(Request $request){
        if ($request->wantsJson()) {

            $datas = Party::query()
            ->with(['media']);

            if ($search = $request->input('search')) {
                $datas->where(function ($q) use ($search) {
                    $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('gst', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            }


            if ($location = $request->input('location')) {
                $datas->where(function ($q) use ($location) {
                    $q->where('city', 'like', "%{$location}%")
                    ->orWhere('state', 'like', "%{$location}%")
                    ->orWhere('district', 'like', "%{$location}%")
                    ->orWhere('pincode', 'like', "%{$location}%");
                });
            }

            
            if ($status = $request->input('status')) {
                $datas->where('status_id', $status);
            }

            if ($type = $request->input('type')) {
                $datas->where('type', $type);
            }

            $orderableColumns = [
                'company_name' => 'company_name',
                'gst'          => 'gst',
                'email'        => 'email',
                'contact_no'   => 'contact_no',
                'city'         => 'city',
                'status'       => 'status_id',
            ];

            if ($request->has('order')) {
                foreach ($request->order as $order) {

                    $columnIndex = $order['column'];
                    $direction   = $order['dir'];
                    $columnName  = $request->columns[$columnIndex]['data'];

                    if (isset($orderableColumns[$columnName])) {
                        $datas->orderBy($orderableColumns[$columnName], $direction);
                    }
                }
            } else {
                $datas->orderBy('company_name', 'asc');
            }

            $recordsTotal = $datas->count();

            $datas = $datas
            ->skip($request->start)
            ->take($request->length)
            ->get();

            $request->merge([
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
            ]);

            return response()->json(new PartyCollection($datas));
        }

        return view('admin.party.list');
    }

    public function create(Request $request ){
        return view('admin.party.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'type' => 'required|in:Client,Vendor,Firm',
            'company_name' => 'required|string|max:255',
            'gst' => 'required|string|max:255|unique:parties,gst',
            'pincode' => 'required|digits:6',
            'state' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string',
            'status' => 'required|in:14,15',
            'stock_on_email' => 'required|in:14,15',
            'login_status' => 'required|in:14,15',

            'kt_docs_repeater_advanced_email' => 'nullable|array',
            'kt_docs_repeater_advanced_email.*.email' => 'nullable|email|max:255',

            'kt_docs_repeater_advanced_contact_no' => 'nullable|array',
            'kt_docs_repeater_advanced_contact_no.*.contact_no' => 'nullable|digits_between:10,15',
        ];

        if ((int) $request->login_status === 14) {
            $rules['username'] = [ 'required', 'regex:/^[a-z0-9]+$/', 'max:255', 'unique:parties,username'];
            $rules['password'] = [ 'required', 'string', 'min:8' ];
        }

        $validated = $request->validate($rules);
        $emails = collect($request->kt_docs_repeater_advanced_email)->pluck('email')->filter()->values()->toArray();
        $contacts = collect($request->kt_docs_repeater_advanced_contact_no)->pluck('contact_no')->filter()->values()->toArray();

        try {
            return DB::transaction(function () use ($request, $emails, $contacts){
                $parties = Party::create([
                    'type' => $request->type,
                    'company_name' => $request->company_name,
                    'gst' => strtoupper($request->gst),
                    'pincode' => $request->pincode,
                    'state' => $request->state,
                    'district' => $request->district,
                    'city' => $request->city,
                    'address' => $request->address,
                    'status_id' => $request->status,
                    'stock_on_email' => $request->stock_on_email,
                    'login_status' => $request->login_status,

                    'email' => $emails ?: null,
                    'contact_no' => $contacts ?: null,
                    'username' => $request->login_status == 14 ? $request->username : null,
                    'password' => $request->login_status == 14 ? Hash::make($request->password) : null,
                    'password_plain' => $request->login_status == 14 ? $request->password : null,
                ]);

                DB::commit();

                return response()->json([
                    'class' => 'bg-success', 
                    'error' => false, 
                    'message' => 'Parties Saved Successfully', 
                    'call_back' => '', 
                    'table_refresh' => true, 
                    'model_id' => 'dataSave'
                ]);
            });

        } catch (\Exception $e) {

            return response()->json([
                'class' => 'bg-danger', 
                'error' => true, 
                'error' => $e->getMessage(),
                'call_back' => '', 
                'table_refresh' => true, 
                'model_id' => ''
            ]);
        }
    }

    


    public function update(Request $request, $id){
        $parties = Party::findOrFail($id);
        $rules = [
            'type' => 'required|in:Client,Vendor,Firm',
            'company_name' => 'required|string|max:255',
            'gst' => 'required|string|max:255|unique:parties,gst,' . $parties->id,
            'pincode' => 'required|digits:6',
            'state' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string',
            'status' => 'required|in:14,15',
            'stock_on_email' => 'required|in:14,15',
            'login_status' => 'required|in:14,15',
            'kt_docs_repeater_advanced_email' => 'nullable|array',
            'kt_docs_repeater_advanced_email.*.email' => 'nullable|email|max:255',
            'kt_docs_repeater_advanced_contact_no' => 'nullable|array',
            'kt_docs_repeater_advanced_contact_no.*.contact_no' => 'nullable|digits_between:10,15',
        ];

        if ((int) $request->login_status === 14) {
            $rules['username'] = ['required','regex:/^[a-z0-9]+$/','max:255','unique:parties,username,' . $parties->id];
            $rules['password'] = ['nullable','string','min:8'];
        }

        $request->validate($rules);

        $emails = collect($request->kt_docs_repeater_advanced_email)
        ->pluck('email')
        ->filter()
        ->values()
        ->toArray();

        $contacts = collect($request->kt_docs_repeater_advanced_contact_no)
        ->pluck('contact_no')
        ->filter()
        ->values()
        ->toArray();

        try {
            return DB::transaction(function () use ($request, $parties, $emails, $contacts) {

                $parties->update([
                    'type' => $request->type,
                    'company_name' => $request->company_name,
                    'gst' => strtoupper($request->gst),
                    'pincode' => $request->pincode,
                    'state' => $request->state,
                    'district' => $request->district,
                    'city' => $request->city,
                    'address' => $request->address,
                    'status_id' => $request->status,
                    'stock_on_email' => $request->stock_on_email,
                    'login_status' => $request->login_status,
                    'email' => $emails ?: null,
                    'contact_no' => $contacts ?: null,
                    'username' => $request->login_status == 14 ? $request->username : null,
                ]);

                if ($request->login_status == 14 && filled($request->password)) {
                    $parties->update([
                        'password' => Hash::make($request->password),
                        'password_plain' => $request->password,
                    ]);
                }

                if ($request->login_status != 14) {
                    $parties->update([
                        'username' => null,
                        'password' => null,
                        'password_plain' => null,
                    ]);
                }

                return response()->json([
                    'class' => 'bg-success', 
                    'error' => false, 
                    'message' => 'Parties Saved Successfully', 
                    'call_back' => '', 
                    'table_refresh' => true, 
                    'model_id' => 'dataSave'
                ]);
            });

        } catch (\Exception $e) {
            return response()->json([
                'class' => 'bg-danger', 
                'error' => true, 
                'error' => $e->getMessage(),
                'call_back' => '', 
                'table_refresh' => true, 
                'model_id' => ''
            ]);
        }
    }




    public function edit($id){
        $party = Party::find($id);
        return view('admin.party.edit', compact('party'));
    }

    public function show(Request $request, $id){
        $parties = Party::find($id);
        return view('admin.party.view', compact('client'));
    }

    public function changeStatus(Request $request, $id){
        $parties = Parties::findOrFail($id);
        if(Parties::where('id', $id)->update(['status_id' => $request->status])){
            $status = $request->status == 14 ? 'Active' : 'In-active';
            return response()->json([
                'message' => 'Parties Updated Successfully.',
                'title' => 'Now Parties is '.$status.'.',
                'class' => 'bg-success'
            ]);
        }
        return response()->json([
            'message' => 'Something went wrong.',
            'title' => 'Parties.',
            'class' => 'bg-ganger'
        ]);
    }

    public function exportLedger($id){
        return Excel::download(new partiesaleLedgerExport($id), 'client_ledger.xlsx');
    }

    public function importCreate(){
        return view('admin.client.import');
    }


    public function importStore(Request $request): JsonResponse{
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        $import = new ClientImport();
        Excel::import($import, $request->file('file'));

        if (!empty($import->errors)) {
            return response()->json([
                'class' => 'bg-danger',
                'error' => true,
                'message' => $import->errors,
                'validation_errors' => $import->errors,
                'call_back' => '',
                'table_refresh' => false,
                'model_id' => 'dataSave',
            ]);
        }

        return response()->json([
            'class' => 'bg-success',
            'error' => false,
            'message' => 'parties imported successfully.',
            'call_back' => '',
            'table_refresh' => true,
            'model_id' => 'dataSave',
        ]);
    }

    public function exportparties(){
        return Excel::download(new partiesExport, 'parties_export.xlsx');
    }

}
