<?php

namespace App\Http\Controllers;

use App\Models\level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashController extends Controller
{
    //
    public  function  __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        return view('dashboard');
    }

    public function add(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|max:30|unique:levels',
            'level' => 'required',
            'sublevel' => 'required',
            'time' => 'required'
        ]);

        $level = new level;
        $level->name = $request->input('name');
        $level->level = $request->input('level');
        $level->sub_level =$request->input('sublevel');
        $level->time = $request->input('time');
        $level->save();

        $request->file('readme')->storeAs('public/levels/'.$request->input('name'),'readme.txt');
        $request->file('constraints')->storeAs('public/levels/'.$request->input('name'), 'constraints.txt');
        $request->file('answers')->storeAs('public/answers/', $request->input('name').'txt');

        Session::flash('message', 'Added New question');
        return view('dashboard');
    }

}
