<?php

namespace AccountHon\Http\Controllers\Auth;

use AccountHon\Http\Controllers\Controller;
use AccountHon\Traits\Convert;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller
{
    use Convert;
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */
    protected $redirectTo = '/institucion';
    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


  /*  public function redirectPath(){
        return route('institucion');
    }*/
}
