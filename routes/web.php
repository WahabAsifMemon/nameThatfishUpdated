<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MainController;


Route::get('/', function () {
    return redirect()->route('home');
})->name('/');

Route::prefix('dashboard')->group(function () {
    Route::get('/', [MainController::class, 'index' ])->name('index');
    Route::get('/privacy', [MainController::class, 'privacy' ])->name('privacy');
    Route::post('/privacy', [MainController::class, 'savePrivacy' ] )->name('privacy.from');
    Route::get('/setting', [MainController::class, 'setting' ] )->name('setting');
    Route::post('/setting', [MainController::class, 'saveSetting' ] )->name('setting.form');
    Route::get('/term', [MainController::class, 'term' ] )->name('term');
    Route::post('/term', [MainController::class, 'saveTerm' ] )->name('term.from');
    Route::get('/about', [MainController::class, 'about' ] )->name('about');
    Route::get('/user_managment', [MainController::class, 'managment' ] )->name('user_managment');
});

Route::put('/users/{id}/status', [MainController::class, 'updateStatus'])->name('updateStatus');



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



