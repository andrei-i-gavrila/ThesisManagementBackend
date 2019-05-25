<?php

namespace App\Mail;

use App\Models\ResetPasswordToken;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var ResetPasswordToken
     */
    private $resetPasswordToken;

    /**
     * Create a new message instance.
     *
     * @param ResetPasswordToken $resetPasswordToken
     */
    public function __construct(ResetPasswordToken $resetPasswordToken)
    {
        //
        $this->resetPasswordToken = $resetPasswordToken;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.resetPassword', ['resetPasswordToken' => $this->resetPasswordToken])
            ->subject('Password reset on ' . env('APP_NAME'));
    }
}
