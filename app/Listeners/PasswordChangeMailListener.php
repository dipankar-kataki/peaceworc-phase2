<?php

namespace App\Listeners;

use App\Events\PasswordChangeMailEvent;
use App\Mail\PasswordChangeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class PasswordChangeMailListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(PasswordChangeMailEvent $event)
    {
        Mail::to($event->email_id)->send(new PasswordChangeMail);
    }
}
