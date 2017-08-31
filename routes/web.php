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

/**
 * Route just for test
 */

Route::get('/test',function (){
    $path = '/home/oem/Projects/Castle/Castle/Castle/storage/app/public/users/1/journal.txt';
    $my_file = fopen($path, "r");
    $values ='';
    while (!feof($my_file)){
        $values .= fgets($my_file);
    }
    fclose($my_file);
    return $values;
});
