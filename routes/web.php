<?php

use App\Events\MessageRecieved;
use App\Events\PublicEvent;
use App\Events\PrivateEvent;
use App\Events\Test;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\LivewireController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\WebsocketController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
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

Route::get('/home', function() {
    return view('welcome');
});
Route::get('/', [MainController::class, 'index'])->name('index');
Route::post('/sesison/add/name', [SessionController::class, 'addName'])->name('addName');
Route::get('/mode/switch', [MainController::class, 'modeSwitch'])->name('modeSwitch');
Route::get('/mode/bot', [MainController::class, 'bot'])->name('bot');
Route::get('/mode/double', [MainController::class, 'double'])->name('double');
Route::get('/reset', [MainController::class, 'reset'])->name('reset');
Route::get('/mode/online/name', [MainController::class, 'name_form'])->name('name_form');
Route::get('/mode/online/list', [MainController::class, 'onlineList'])->name('onlineList');
Route::get('/mode/online/wait', [MainController::class, 'onlineWait'])->name('onlineWait');
Route::post('/mode/online/room/join', [MainController::class, 'onlineJoin'])->name('onlineJoin');
Route::post('/mode/online/room/leave', [MainController::class, 'onlineLeave'])->name('onlineLeave');
Route::get('/mode/online/room/battle', [MainController::class, 'onlineBattle'])->name('onlineBattle');
Route::post('/mode/online/create', [MainController::class, 'roomCreate'])->name('roomCreate');

// テスト
Route::get('/test', [MainController::class, 'test'])->name('test');
Route::get('/livewire', function () {
    return view('main.livewire');
})->name('livewire');
Route::get('/session/all', function() {
    $all = session()->all();
    $id = session()->getId();
    dd($all);
})->name('session_all');

// Ajax
Route::post('/ajax/send', [AjaxController::class, 'send'])->name('ajaxSend');


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
