<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;

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

    protected $messages = [
        'email.required' => 'El campo correo electrónico es requerido',
        'email.email' => 'El formato del correo electrónico es incorrecto',
    ];

    use SendsPasswordResetEmails;

    /**
    * Validate the email for the given request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return void
    */
    protected function validateEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email'], $this->messages);
    }

    /**
    * Display the form to request a password reset link.
    *
    * @return \Illuminate\Http\Response
    */
    public function showLinkRequestForm()
    {
    return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email'], $this->messages);
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        if ($response === Password::RESET_LINK_SENT) {
            return back()->with([
                'status' => trans($response),
                'email' => $request->email
            ]);
        }

        // If an error was returned by the password broker, we will get this message
        // translated so we can notify a user of the problem. We'll redirect back
        // to where the users came from so they can attempt this process again.
        return back()->withErrors(
            ['email' => trans($response)]
        );
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
}
