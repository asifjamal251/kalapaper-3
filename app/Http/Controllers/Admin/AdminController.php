<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Admin\AdminCollection;
use App\Models\Admin;
use App\Models\AdminIp;
use App\Models\Role;
use Auth;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller{

    public function index(Request $request){
    if ($request->wantsJson()) {

        $datas = Admin::query()
            ->whereNotIn('admins.role_id', [1])
            ->with(['role', 'media']);
        if ($search = $request->input('search')) {
            $datas->where(function ($q) use ($search) {
                $q->where('admins.name', 'like', "%{$search}%")
                  ->orWhere('admins.username', 'like', "%{$search}%")
                  ->orWhere('admins.email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $datas->where('admins.role_id', $role);
        }

        if ($status = $request->input('status')) {
            $datas->where('admins.status_id', $status);
        }

        $orderableColumns = [
            'name'     => 'admins.name',
            'username' => 'admins.username',
            'email'    => 'admins.email',
            'status'   => 'admins.status_id',
            'role'     => 'roles.name',
        ];

        if ($request->has('order')) {

            $columnIndex = $request->order[0]['column'];
            $direction   = $request->order[0]['dir'];
            $columnName  = $request->columns[$columnIndex]['data'];

            if (isset($orderableColumns[$columnName])) {
                if ($columnName === 'role') {
                    $datas->leftJoin('roles', 'roles.id', '=', 'admins.role_id')
                          ->select('admins.*', 'roles.name');
                }

                $datas->orderBy($orderableColumns[$columnName], $direction);
            }

        } else {
            $datas->orderBy('admins.id', 'asc');
        }

        $recordsTotal = $datas->count();

        $datas = $datas
            ->skip($request->start)
            ->take($request->length)
            ->get();

        $request->merge([
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
        ]);

        return response()->json(new AdminCollection($datas));
    }

    return view('admin.admin.list');
}

    public function create(Request $request )
    {
        $roles = Role::whereNotIn('id',[1])->select(['id','name'])->get()->pluck('name','id')->toArray();
        return view('admin.admin.create',compact('roles'));
    }

     public function show(Request $request, $id ){
        $admin = Admin::where('id', $id)->first();
        return view('admin.admin.view',compact('admin'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',

            'username' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+$/',
                'unique:admins,username',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            ],

            'status' => 'required',
            'role' => 'required',
            'enabled_2fa' => 'required',
            'ip_enabled' => 'required',
            'login_time_restriction_enabled' => 'required',
            'login_allowed_from' => 'nullable|required_if:login_time_restriction_enabled,14',
            'login_allowed_to'   => 'nullable|required_if:login_time_restriction_enabled,14',
        ];

        if ((int) $request->ip_enabled === 14) {
            $rules['kt_docs_repeater_advanced'] = 'required|array|min:1';
            $rules['kt_docs_repeater_advanced.*.ips'] = 'required|ip';
            $rules['kt_docs_repeater_advanced.*.notes'] = 'nullable|string|max:200';
        }

        $messages = [
            'kt_docs_repeater_advanced.required' =>
            'At least one IP address is required.',

            'kt_docs_repeater_advanced.*.ips.required' =>
            'IP address is required.',

            'kt_docs_repeater_advanced.*.ips.ip' =>
            'Please enter a valid IP address.',

            'kt_docs_repeater_advanced.*.notes.max' =>
            'Notes may not be greater than 200 characters.',
        ];

        $this->validate($request, $rules, $messages);

        try {
            return DB::transaction(function () use ($request) {

                $admin = Admin::create([
                    'username' => $request->username,
                    'role_id' => $request->role,
                    'password' => bcrypt($request->password),
                    'plain_password' => $request->password,
                    'name' => $request->name,
                    'email' => $request->email,
                    'status_id' => $request->status,
                    'ip_enabled' => $request->ip_enabled,
                    'google2fa_enabled' => $request->enabled_2fa,
                    'login_time_restriction_enabled' => $request->login_time_restriction_enabled,
                    'login_allowed_from' => $request->login_allowed_from,
                    'login_allowed_to'  => $request->login_allowed_to,
                ]);

                if ((int) $request->ip_enabled === 14) {
                    foreach ($request->kt_docs_repeater_advanced as $row) {
                        AdminIp::create([
                            'admin_id' => $admin->id,
                            'ip_address' => $row['ips'],
                            'notes' => $row['notes'] ?? null,
                        ]);
                    }
                }

                if ((int) $request->enabled_2fa === 14) {
                    return response()->json([
                        'class' => 'bg-success',
                        'error' => false,
                        'message' => 'Admin Saved Successfully',
                        'call_back' => route('admin.admin.2fa.setup', $admin->id),
                        'table_refresh' => true,
                        'model_id' => 'dataSave',
                    ]);
                }

                return response()->json([
                    'class' => 'bg-success',
                    'error' => false,
                    'message' => 'Admin Saved Successfully',
                    'call_back' => route('admin.admin.index'),
                    'table_refresh' => true,
                ]);
            });

        } catch (\Exception $e) {
            return response()->json([
                'class' => 'bg-danger',
                'error' => true,
                'message' => $e->getMessage(),
                'call_back' => '',
                'table_refresh' => true,
            ]);

        }
    }

    public function edit(Request $request, $id ){
        $admin = Admin::where('id', $id)->first();
        return view('admin.admin.edit',compact('admin'));
    }

    public function update(Request $request, $id){
        $admin = Admin::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            ],
            'status' => 'required',
            'role' => 'required',
            'enabled_2fa' => 'required',
            'ip_enabled' => 'required',
            'login_time_restriction_enabled' => 'required',
            'login_allowed_from' => 'nullable|required_if:login_time_restriction_enabled,14',
            'login_allowed_to'   => 'nullable|required_if:login_time_restriction_enabled,14',
        ];

        if ((int) $request->ip_enabled === 14) {
            $rules['kt_docs_repeater_advanced'] = 'required|array|min:1';
            $rules['kt_docs_repeater_advanced.*.ips'] = 'required|ip';
            $rules['kt_docs_repeater_advanced.*.notes'] = 'nullable|string|max:200';
        }

        $this->validate($request, $rules);

        try {
            return DB::transaction(function () use ($request, $admin) {

                $admin->update([
                    'role_id' => $request->role,
                    'name' => $request->name,
                    'email' => $request->email,
                    'status_id' => $request->status,
                    'ip_enabled' => $request->ip_enabled,
                    'login_time_restriction_enabled' => $request->login_time_restriction_enabled,
                    'login_allowed_from' => $request->login_allowed_from,
                    'login_allowed_to'  => $request->login_allowed_to,
                ]);

                if ($request->filled('password')) {
                    $admin->update([
                        'password' => bcrypt($request->password),
                        'plain_password' => $request->password,
                    ]);
                }

                if ((int) $request->ip_enabled === 14) {

                    $requestedIps = collect($request->kt_docs_repeater_advanced)
                    ->pluck('ips')
                    ->toArray();

                    AdminIp::where('admin_id', $admin->id)
                    ->whereNotIn('ip_address', $requestedIps)
                    ->delete();

                    foreach ($request->kt_docs_repeater_advanced as $row) {
                        AdminIp::updateOrCreate(
                            [
                                'admin_id' => $admin->id,
                                'ip_address' => $row['ips'],
                            ],
                            [
                                'notes' => $row['notes'] ?? null,
                            ]
                        );
                    }
                }

                if ((int) $request->enabled_2fa === 14 && !$admin->google2fa_secret) {
                    return response()->json([
                        'class' => 'bg-warning',
                        'error' => false,
                        'message' => 'Please setup 2FA first',
                        'call_back' => route('admin.admin.2fa.setup', $admin->id),
                        'table_refresh' => false,
                    ]);
                }

                $admin->update([
                    'google2fa_enabled' => $request->enabled_2fa
                ]);

                return response()->json([
                    'class' => 'bg-success',
                    'error' => false,
                    'message' => 'Admin Updated Successfully',
                    'call_back' => route('admin.admin.index'),
                    'table_refresh' => true,
                ]);
            });

        } catch (\Exception $e) {
            return response()->json([
                'class' => 'bg-danger',
                'error' => true,
                'message' => $e->getMessage(),
                'call_back' => '',
                'table_refresh' => true,
            ]);
        }
    }

    public function profileUpdate(Request $request) {

        $this->validate($request,[
            'name' => 'required',
        ]);

        $admin = Auth::guard('admin')->user();

        $admin->name = $request->name;
        $admin->mobile = $request->mobile_no;
        $admin->gender = $request->gender;
        $admin->state = $request->state;
        $admin->city = $request->city;
        $admin->pincode = $request->zipcode;
        $admin->address = $request->address;
        $admin->bio = $request->bio;
        $admin->date_of_birth = Carbon::parse($request->date_of_birth)->format('Y-m-d');

        if($admin->save()){
            return response()->json(['message' => 'Profile  Updated', 'class' => 'success']);
        }

        return response()->json(['message' => 'Whoops, looks like something went wrong ! Try again ...', 'class' => 'error']);
    }

    public function destroy(Request $request, Admin $admin)
    {

        if($admin->delete()){

            return response()->json(['message' => 'User deleted successfully ...', 'class' => 'success', 'error' => false, 'title' => 'Item Deleted!', 'timer' => 2000]);

        }
        return response()->json(['message' => 'Whoops, looks like something went wrong ! Try again ...', 'class' => 'error']);
    }

    public function profilePhotoUpdate(Request $request, $id)
    {

        $request->validate([
            'avatar' => 'required',   
        ]);

        $admin = Auth::guard('admin')->user();

        if($request->hasFile('avatar')){
            $image_name = time().'.'.$request->file('avatar')->getClientOriginalExtension();
            $image = $request->file('avatar')->storeAs('media/admin', $image_name);
            $storage_type = env('FILESYSTEM_DISK');
            if($storage_type == 's3'){
                $admin->avatar = config('appsetting.media_url').$image;
            }else{
                $admin->avatar = 'storage/'.$image;
            }

        }

        if($admin->save()){ 
            return response()->json(['message' => 'Profile Photo Updated', 'class' => 'success']);
        }

        return response()->json(['message' => 'Whoops, looks like something went wrong ! Try again ...', 'class' => 'error']);
    }

    public function profileCoverPhotoUpdate(Request $request, $id)
    {

        $request->validate([
            'cover_photo' => 'required',   
        ]);

        $admin = Auth::guard('admin')->user();

        if($request->hasFile('cover_photo')){
            $image_name = time().'.'.$request->file('cover_photo')->getClientOriginalExtension();
            $image = $request->file('cover_photo')->storeAs('media/admin', $image_name);
            $storage_type = env('FILESYSTEM_DISK');
            if($storage_type == 's3'){
                $admin->cover = config('appsetting.media_url').$image;
            }else{
                $admin->cover = 'storage/'.$image;
            }
        }

        if($admin->save()){ 
            return response()->json(['message' => 'Profile Photo Updated', 'class' => 'success']);
        }

        return response()->json(['message' => 'Whoops, looks like something went wrong ! Try again ...', 'class' => 'error']);
    }

    public function updatePassword(Request $request)
    {

        $this->validate($request,[
            'current_password' => 'required|min:6',
            'new_password' => 'required|min:6|confirmed',

        ]);

        if(Hash::check($request->current_password, Auth::guard('admin')->user()->password)) {
            $admin = Auth::guard('admin')->user();
            $admin->password = bcrypt($request->new_password);
            if($admin->save()){
                return response()->json(['message' => 'Password changed successfully.', 'class' => 'success']);

            }
            return response()->json(['message' => 'Whoops, looks like something went wrong ! Try again ...', 'class' => 'error']);

        }
        return response()->json(['message' => 'Old Password is not match', 'class' => 'error']);

    }

    public function profile(Request $request)
    {

        $admin = Auth::guard('admin')->user();
        return view('admin.admin.profile', compact('admin'));
    }

    public function changePassword(Request $request, $id)
    {
        return view('admin.admin.change-password');
    }

    public function setup2FA($id){
        $admin = Admin::where('id', $id)->first();
        $google2fa = app('pragmarx.google2fa');
        $secretKey = $google2fa->generateSecretKey();

        $qrCodeUrl = $google2fa->getQRCodeInline(
            config('app.name'),
            $admin->email,
            $secretKey
        );

        return view('admin.admin.2fa_setup', ['secret' => $secretKey, 'qrCodeUrl' => $qrCodeUrl, 'id' => $id, 'admin' => $admin]);
    }

    public function enable2FA(Request $request, $id){
        $request->validate([
            'secret' => 'required',
            'one_time_password' => 'required',
        ]);

        $google2fa = app('pragmarx.google2fa');

        $valid = $google2fa->verifyKey($request->secret, $request->one_time_password);

        if ($valid) {
            $admin = Admin::where('id', $id)->first();
            $admin->google2fa_secret = $request->secret;
            $admin->google2fa_enabled = 14;
            $admin->status_id = 14;
            $admin->save();

            return redirect()->route('admin.admin.index')->with('status', '2FA enabled successfully.');
        } else {
            return redirect()->back()->withErrors(['one_time_password' => 'Invalid OTP']);
        }
    }

}
