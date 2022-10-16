<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
    * Get uerername field
    */
    public function username():string
    {
        $field = 'email';
        $field = (filter_var(request()->email, FILTER_VALIDATE_EMAIL) || !request()->email) ? 'email' : 'username';
       

        request()->merge([$field => request()->email]);        
        return $field;
    }

    
    /**
     * Get the needed authorization credentials from the request.
     * Only the active users will be able to login
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request):array
    {

       $creds = $request->only($this->username(), 'password');
       $creds['active'] = true;
       return $creds;
    }
}
