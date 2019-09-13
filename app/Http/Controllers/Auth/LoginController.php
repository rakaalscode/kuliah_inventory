<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        // $this->validate($request, [
        //     'email'   => 'required|email',
        //     'password'  => 'required'
        // ]);

        dd('ok');

        try{
            $email = $request->email;
            $password = $request->password;

            $user = User::where('email',$email)->first();
            if(Hash::check($password, $user->password)){
                Auth::login($user);
                return redirect()->route('home')->with('message','Login berhasil, Selamat datang '.Auth::user()->name);
            }else{
                return redirect()->back()->with('error','login gagal, Akun carsworld tidak terdaftar');
            }
            return redirect()->back()->with('error','login gagal, nomor telepon atau password salah');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error','login gagal, nomor telepon atau password salah');
        }

    }
}
