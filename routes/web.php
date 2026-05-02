<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialLoginController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/admin.php';
require __DIR__ . '/user.php';

Route::get('/', function () {
    return to_route('login');
});

// Route::middleware('admin')->group(function () {
//     Route::get('home', [AdminController::class, 'home'])->name('adminHome');

// });

Route::get('/dashboard', function () {
    return view('test');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// Social Login

Route::get('/auth/{provider}/redirect', [SocialLoginController::class, 'redirect'])->name('socialLogin');

Route::get('/auth/{provider}/callback', [SocialLoginController::class, 'callback'])->name('socialCallback');
