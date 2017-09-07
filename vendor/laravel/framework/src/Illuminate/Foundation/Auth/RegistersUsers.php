<?php

namespace Illuminate\Foundation\Auth;

use App\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

trait RegistersUsers
{
    use RedirectsUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        //calculate path for the user if the dir already exists (unlikely) delete it and create new dir
        $default = 'public/users/default';
        $user_id = $user['id'];
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
        $token = Hash::make($user['email'].$user['remember_token']);
        $token=strtr($token, ['/' => '']);
        $link = URL::to('/').'/verify/'.$token;

        Mail::send(['text'=> 'mail'], ['link'=> $link], function ($message) use ($user){
            $message->to( $user['email'],$user['name'])->subject('Verify Email');
            $message->from('mail@castle.akhilerm.com', 'admin');
        });

        $save_token = new Token;
        $save_token['token'] = $token;
        $save_token['user_id'] = $user['id'];
        $save_token->save();

        //Flash in seesion to show in login page
        $request->session()->flash('message', 'An email has been sent to your email account to verify email.');

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
    }
}
