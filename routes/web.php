<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/shell','ShellContoller@shell')->name('shell');

Route::post('/editor', 'EditorController@index')->name('editor');

Route::post('/timeout','HomeController@timeout')->name('timeout');

Route::get('verify/{token}',function ($token){

    $token_data = \App\Token::where('token', $token)->first();
    $user_id = $token_data['user_id'];
    $token_data->delete();

    $user = \App\Models\user::find($user_id);
    $user['active'] = 1;
    $user->save();

    \Illuminate\Support\Facades\Session::flash('message', 'Your account has been activated please login');
    return redirect('login');

})->middleware('guest');
