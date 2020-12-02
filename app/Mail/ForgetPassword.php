<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $token;
    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
        $this->subject = 'Password Reset Request for MIS.';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('auth.emails.password')->with([
            'user' => $this->user,
            'token' => $this->token
        ]);
    }
}
