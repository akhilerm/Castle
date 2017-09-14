<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EditorController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $inputs = $request->all();

        $msg = 'failed';

        if (Storage::has($inputs['file'])){
                Storage::put($inputs['file'], $inputs['value']);
                $msg = 'Saved';
        }

        return response()->json(['MSG' => $msg]);
    }
}
