<?php

use App\Events\ChatMessageEvent;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::view('/admin', 'admin');
Route::view('/user', 'user');

// Admin sends message
Route::post('/send-message', function () {
    $message = request('message');
    $from = request('from'); // "Admin" or "User"

    event(new ChatMessageEvent($message, $from));

    return ['status' => 'Message sent'];
});