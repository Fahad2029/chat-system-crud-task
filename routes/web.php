<?php

use App\Events\ChatMessageEvent;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


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


Route::get('/login', [AuthController::class,'showLogin'])->name('login');
Route::post('/login', [AuthController::class,'login'])->name('login.submit');
Route::post('/logout', [AuthController::class,'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::view('/superadmin', 'superadmin');
    Route::view('/superuser', 'superuser');

    // Task CRUD API
    Route::get('/tasks',[TaskController::class,'index']);
    Route::post('/tasks',[TaskController::class,'store']);
    Route::put('/tasks/{task}',[TaskController::class,'update']);
    Route::delete('/tasks/{task}',[TaskController::class,'destroy']);
});