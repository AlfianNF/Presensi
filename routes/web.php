<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/',[AuthController::class,'loginPage'])->name('loginPage');
Route::post('/',[AuthController::class,'login'])->name('login');

Route::middleware(['token.auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::get('/dashboard/user',[DashboardController::class,'user'])->name('user');

    Route::get('/dashboard/setting', function () {
        return view('dashboard.setting');
    })->name('setting');

    Route::get('/dashboard/profil', function () {
        return view('dashboard.profil');
    })->name('profil');
});