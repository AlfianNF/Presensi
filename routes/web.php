<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/',[AuthController::class,'loginPage'])->name('loginPage');
Route::post('/',[AuthController::class,'login'])->name('login');

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->name('dashboard.index');

Route::get('/dashboard/user',[DashboardController::class,'user'])->name('dashboard.user');
Route::get('/dashboard/setting',[DashboardController::class,'setting'])->name('dashboard.setting');
Route::get('/dashboard/profil',[DashboardController::class,'profil'])->name('dashboard.profil');
