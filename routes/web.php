<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MessageHistoryController;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',

], function ($router) {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'api/v1',

], function ($router) {

    Route::get('/location', [LocationController::class, 'show']);
    Route::post('/location', [LocationController::class, 'store']);
    Route::put('/location', [LocationController::class, 'update']);

    Route::post('/message', [MessageController::class, 'store']);
    Route::put('/message', [MessageController::class, 'update']);
    Route::get('/message', [MessageController::class, 'show']);
    Route::get('/message/type', [MessageController::class, 'type']);

    Route::get('/history/message/all/{id?}', [MessageHistoryController::class, 'show']);
    Route::post('/history/message/response', [MessageHistoryController::class, 'store']);
    Route::put('/history/message/edit', [MessageHistoryController::class, 'update']);

});

Route::get('/', function () {
    return view('welcome');
});
