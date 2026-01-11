<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\PriceCategoryManagement;
use App\Livewire\Admin\ClientManagement;
use App\Livewire\Admin\ProjectManagement;
use App\Livewire\Admin\InvoiceManagement;
use App\Livewire\Public\ProjectTracker;
use App\Livewire\Auth\Login;
use App\Http\Controllers\Admin\LogoutController;

// Public Routes
Route::get('/project/{token}', ProjectTracker::class)->name('project.public');

// Auth Routes
Route::get('/login', Login::class)->name('login');

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/users', UserManagement::class)->name('admin.users');

    // Freelance Project Manager Routes
    Route::get('/price-categories', PriceCategoryManagement::class)->name('admin.price-categories');
    Route::get('/clients', ClientManagement::class)->name('admin.clients');
    Route::get('/projects', ProjectManagement::class)->name('admin.projects');
    Route::get('/invoices', InvoiceManagement::class)->name('admin.invoices');

    Route::post('/logout', [LogoutController::class, '__invoke'])->name('logout');
});