<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\Verify;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use App\Token;

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

        //calculate path for the user if the dir already exists (unlikely) delete it and create new dir
        $default = 'public/users/default';
        $user_id = $event->user->id;
        $user_dir = "public/users/$user_id";
        if (Storage::has($user_dir)){
            Storage::deleteDirectory($user_dir);
        }

        //Find all files in default  dir and make a copy of it to main folder
        $files = Storage::allFiles($default);
        foreach ($files as $file){
            $new_file = strtr($file, [ $default => $user_dir ]);
            Storage::copy($file, $new_file);
        }

        //Make Token and send mail to  user for verifying the email.
        $token = Hash::make($event->user->id).$event->user->name;
        $token = strtr($token, ['/' => '']);
        $link = URL::to('/').'/verify/'.$token;

        Mail::to($event->user->email)->send(new Verify($link));

        $save_token = new Token;
        $save_token->token = $token;
        $save_token->user_id = $event->user->id;
        $save_token->save();
    }
}
