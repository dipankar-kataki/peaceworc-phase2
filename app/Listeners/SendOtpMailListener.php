<?php

namespace App\Listeners;

use App\Events\SendOtpMailEvent;
use App\Mail\SendEmailVerificationOTPMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendOtpMailListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\SendOtpMailEvent  $event
     * @return void
     */
    public function handle(SendOtpMailEvent $event)
    {
        Mail::to($event->email_id)->send(new SendEmailVerificationOTPMail($event->otp));
    }
}
