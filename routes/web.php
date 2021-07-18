<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\SessionController;
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


Route::get('/', [MainController::class, 'index'])->name('index');
Route::post('/sesison/add/name', [SessionController::class, 'addName'])->name('addName');
Route::get('/mode/switch', [MainController::class, 'modeSwitch'])->name('modeSwitch');
Route::get('/mode/bot', [MainController::class, 'bot'])->name('bot');
Route::get('/mode/double', [MainController::class, 'double'])->name('double');
Route::get('/reset', [MainController::class, 'reset'])->name('reset');
Route::get('/mode/online/name', [MainController::class, 'name_form'])->name('name_form');
Route::get('/mode/online/list', [MainController::class, 'onlineList'])->name('onlineList');
Route::post('/mode/online/create', [MainController::class, 'roomCreate'])->name('roomCreate');

// テスト
Route::get('/test', [MainController::class, 'test'])->name('test');


// Ajax
Route::post('/ajax/send', [AjaxController::class, 'send'])->name('ajaxSend');