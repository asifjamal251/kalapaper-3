<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request){
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username or Email is required.',
            'password.required' => 'Password is required.',
        ]);

        $loginInput = $request->username;

        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $admin = Admin::where($fieldType, $loginInput)->first();

        if (!$admin) {
            return back()->withErrors([
                'username' => 'No account found with this Username or Email.'
            ])->withInput();
        }

        if ($admin->status_id == 15) {
            return back()->withErrors([
                'username' => 'Your account is inactive. Please contact the owner.'
            ]);
        }

        if (Auth::guard('admin')->attempt([
            $fieldType => $loginInput,
            'password' => $request->password,
            'status_id' => 14,
        ], $request->boolean('remember_me'))) {

            if (Auth::guard('admin')->user()->google2fa_enabled == 14) {
                session(['admin_id' => Auth::guard('admin')->id()]);
                Auth::guard('admin')->logout();
                return redirect()->route('admin.2fa.verify');
            }

            return redirect()->route('admin.dashboard.index');
        }

        return back()->withErrors([
            'password' => 'Incorrect password.'
        ])->withInput();
    }

    public function show2FAVerificationForm(){
        if (session()->has('admin_id')) {
            return view('admin.auth.2fa_verify');
        }
        return redirect()->route('admin.login.form');
    }

    public function verify2FA(Request $request){
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        if (!session()->has('admin_id')) {
            return redirect()->route('admin.login.form');
        }

        $admin = Admin::find(session('admin_id'));

        if (!$admin || !$admin->google2fa_secret) {
            return redirect()->route('admin.login.form');
        }

        $google2fa = app('pragmarx.google2fa');

        $isValid = $google2fa->verifyKey(
            $admin->google2fa_secret,
            $request->otp
        );

        if (!$isValid) {
            return back()->withErrors([
                'otp' => 'Invalid OTP'
            ]);
        }

        Auth::guard('admin')->login($admin);
        session()->forget('admin_id');

        return redirect()->route('admin.dashboard.index');
    }

    public function logout()
    {
        $this->guard()->logout();
        return redirect()->route('admin.login.form');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('admin.guest', ['except' => 'logout']);
    // }

    protected function guard()
    {
        return Auth::guard('admin');
    }

}
