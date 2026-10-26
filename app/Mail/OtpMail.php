<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $mailSubject;

    public function __construct($otp, $mailSubject = 'Your ChampionClub Admin Login Code')
    {
        $this->otp = $otp;
        $this->mailSubject = $mailSubject;
    }

    public function build()
    {
        return $this->subject($this->mailSubject)
                    ->view('emails.otp');
    }
}
