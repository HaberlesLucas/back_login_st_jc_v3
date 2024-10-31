<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;


    // public $token;
    public $frontendUrl;

    public function __construct($frontendUrl)
    {
        $this->frontendUrl = $frontendUrl;
    }

    public function build()
    {
        return $this->view('emails.password_reset')
            ->with(['url' => $this->frontendUrl]);
    }

}

    // public function __construct($token)
    // {
    //     $this->token = $token;
    // }

    // public function build()
    // {
    //     $resetUrl = url('/reset-password?token=' . $this->token);
    //     return $this->view('emails.password_reset')
    //         ->with([
    //             'resetUrl' => $resetUrl,
    //         ]);
    // }

