<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\MainController;
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
Route::get('/reset', [MainController::class, 'reset'])->name('reset');





// Ajax
Route::post('/ajax/send', [AjaxController::class, 'send'])->name('ajaxSend');