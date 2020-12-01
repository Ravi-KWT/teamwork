<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Response;
use Validator;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    // code for forgot password method over ride
    public function sendResetLinkEmail(Request $request)
    {
        $credentials = request()->validate(['email' => 'required|email']);
        $response = Password::sendResetLink($credentials);
        // $this->validateSendResetLinkEmail($request);
        // $broker = $this->getBroker();
        // $response = Password::broker($broker)->sendResetLink(
        //     $this->getSendResetLinkEmailCredentials($request),
        //     $this->resetEmailBuilder()
        // );
        switch ($response) {
            case Password::RESET_LINK_SENT:
                return response()->json(array('success'=>true ,'msg'=>trans($response)));
                
            case Password::INVALID_USER:
            default:
                return response()->json(array('error'=>true , 'msg'=>trans($response)));
        }
    }
}
