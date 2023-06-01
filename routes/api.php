<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SupportController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});
Route::middleware('auth:api')->group(function () {
Route::get('logout', [AuthController::class, 'logout']);
Route::post('profile', [AuthController::class, 'profile']);
Route::post('change-pass', [AuthController::class, 'changePass']);
});
Route::post('mail', [AuthController::class, 'send']);
Route::post('forgot-password', [AuthController::class, 'forgetPass']);
Route::delete('user/{id}',  [AuthController::class, 'delete']);
Route::post('add_role', [AuthController::class, 'add_role']);

// Support Api
Route::post('/support', [SupportController::class, 'create']);

// Delete Account
Route::delete('/user/{id}', [AuthController::class, 'delete']);








