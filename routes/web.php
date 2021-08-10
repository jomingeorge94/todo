<?php

use Illuminate\Support\Facades\Route;

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
    return view('tasks');
});

Route::post('retrieve-task', 'App\Http\Controllers\TaskController@getTasks')->name('retrieve-task');
Route::post('task-handler', 'App\Http\Controllers\TaskController@taskHandler')->name('task-handler');
Route::post('delete-task', 'App\Http\Controllers\TaskController@deleteTask')->name('delete-task');
Route::post('complete-task', 'App\Http\Controllers\TaskController@completeTask')->name('complete-task');