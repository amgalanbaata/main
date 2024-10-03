<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\StaticUrlController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/posts', [ApiController::class, 'insert']);
Route::post('/myposts', [ApiController::class, 'myPosts']);
Route::post('/count', [ApiController::class, 'count']);
Route::post('/sendCode', [ApiController::class, 'sendCode']);
Route::post('/email', [ApiController::class, 'email']);
Route::post('/password', [ApiController::class, 'password']);
Route::post('/typeName', [ApiController::class, 'typeName']);
Route::post('/statusName', [ApiController::class, 'statusName']);
