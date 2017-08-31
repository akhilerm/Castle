<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\user;
use App\Models\level;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = user::find(Auth::id())->first();
        $time = level::find($user['level_id'])->first()->time;
        $startTime = $user['updated_at'];
        if ($user['status'] === 'PLAYING'){
            $result = $time + strtotime($startTime);
        } else{
            $result =  0;
        }
        return view('home',['time' => $result]);
    }


    /**
     * Change user status on timeout and delete question folder
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */

    public function timeout(Request $request){

        if ($request->ajax()){

            $user = user::find(Auth::id())->first();
            $question_name=level::find($user['level_id'])->first()['name'];
            $user['status'] = 'TIMEOUT';
            $user->save();
            Storage::deleteDirectory('public/users/'.Auth::id().'/'.$question_name);
            Session::put('pwd', '~');
            return response()->json([ 'result' => true]);

        } else{
            return view('error');
        }

    }
}
