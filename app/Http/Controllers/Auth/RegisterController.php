<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use App\Token;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => array('required', 'string', 'max:30', 'unique:users', 'regex:/^[0-9a-zA-z]*$/'),
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'date' => 'required|date',
            'country' => 'required|string|max:20',
            'phone' => 'required|max:13',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'date' => $data['date'],
            'country' => $data['country'],
            'phone' => $data['phone']
        ]);

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

        event(new UserRegistered($user, $link));

        $save_token = new Token;
        $save_token->token = $token;
        $save_token->user_id = $user['id'];
        $save_token->save();

        return $user;

    }
}
