<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\ReviewController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.index');
});

Route::get('/dashboard', function () {
    return view('admin.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');
Route::post('/admin/login', [AdminController::class, 'adminLogin'])->name('admin.login');
Route::get('/verify', [AdminController::class, 'showVerificationForm'])->name('custom.verification.form');
Route::post('/verificationVerify', [AdminController::class, 'verificationVerify'])->name('custom.verification.verify');

// login first then you can go to profile page
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AdminController::class, 'adminProfile'])->name('admin.profile');
    Route::post('/profile/store', [AdminController::class, 'profileStore'])->name('profile.store');
    Route::post('/admin/password/update', [AdminController::class, 'adminPasswordUpdate'])->name('admin.password.update');
});
// login first then you can go to all review page
Route::middleware('auth')->group(function () {
    Route::controller(ReviewController::class)->group(function(){
        Route::get('/all/review','allReview')->name('all.review');
    });
});
