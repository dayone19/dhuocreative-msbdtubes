<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Mail\ForgotPasswordOtpmail;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(string $otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Kode OTP Reset Password DhuoCreative')
                    ->html(
                        '<h2>Reset Password DhuoCreative</h2>
                        <p>Gunakan kode OTP berikut ini untuk reset password</p>
                        <h1>' . e($this->otp) .'</h1>
                        <p>Kode ini berlaku selama 5 menit. jangan bagikan kode ini kepada siapa pun.</p>'
                    );
    }
}
