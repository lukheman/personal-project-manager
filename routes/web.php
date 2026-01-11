<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Auth\Login;
use App\Http\Controllers\Admin\LogoutController;
// Auth Routes
Route::get('/login', Login::class)->name('login');

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', UserManagement::class)->name('admin.users');
    Route::post('/logout', [LogoutController::class, '__invoke'])->name('logout');
});