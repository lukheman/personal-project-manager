<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\PriceCategoryManagement;
use App\Livewire\Admin\ClientManagement;
use App\Livewire\Admin\ProjectManagement;
use App\Livewire\Admin\InvoiceManagement;
use App\Livewire\Public\ProjectTracker;
use App\Livewire\Public\LandingPage;
use App\Livewire\Auth\Login;
use App\Http\Controllers\Admin\LogoutController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\AttachmentController;

// Public Routes
Route::get('/', LandingPage::class)->name('home');
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

    // Invoice PDF Routes
    Route::get('/invoices/{project}/download', [InvoiceController::class, 'download'])->name('admin.invoices.download');
    Route::get('/invoices/{project}/preview', [InvoiceController::class, 'preview'])->name('admin.invoices.preview');

    // Attachment Routes
    Route::get('/attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('admin.attachments.download');

    Route::post('/logout', [LogoutController::class, '__invoke'])->name('logout');
});

