<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class RegisteredListener implements ShouldQueue
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
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        Mail::send(['text'=> 'mail'], ['link'=> $event->link], function ($message) use ($event){
            $message->to( $event->user->email, $event->user->name)->subject('Verification Email');
            $message->from('mail@castle.akhilerm.com', 'admin');
        });
    }
}
