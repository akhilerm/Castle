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
        if(!Session::has('pwd')){
            Session::put('pwd','~');
        }
        $user = user::find(Auth::id());
        $time = level::find($user['level_id'])->time;
        error_log('TIME IN INDEX:'.$time);
        $startTime = $user['updated_at'];
        error_log('STARTIME_INDEX:'.$startTime);
        if ($user['status'] == 'PLAYING'){
            $result = $time + strtotime($startTime);
            error_log('RESUKLT in INDEX:'.$result);
        } else{
            $result =  0;
            error_log('RESULT 0');
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
            $user = user::find(Auth::id());
            error_log('LEVEL_ID:'.$user['level_id']);
            $question_name=level::where('id', '=', $user['level_id'])->first()['name'];
            error_log('Q_NAME:'.$question_name);
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
