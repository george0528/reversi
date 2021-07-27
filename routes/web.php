<?php

use App\Events\MessageRecieved;
use App\Events\PublicEvent;
use App\Events\PrivateEvent;
use App\Events\Test;
use App\Http\Controllers\AjaxController;
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
Route::get('/echo', function () {
    // (function($count) {
    //     echo $count;
    //     is_null($count) ? Redis::set('count', 0) : Redis::set('count', $count+1);
    // })(Redis::get('count'));
    broadcast(new MessageRecieved);
    return '最初の原因';
});
Route::get('public', function () {
    event(new PublicEvent);
    return 'public';
});
Route::get('private', function () {
    event(new PrivateEvent);
    return 'private';
});
Route::get('/fire', function() {
    $m = 'aaaaaa';
    $m = json_encode($m);
    broadcast(new Test($m));
    return 'fire!!!!!!!!!!!';
});
Route::post('/websocket/test', [WebsocketController::class, 'test'])->name('webTest');

// Ajax
Route::post('/ajax/send', [AjaxController::class, 'send'])->name('ajaxSend');


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
