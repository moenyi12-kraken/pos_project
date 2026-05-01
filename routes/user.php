<?php

use App\Http\Controllers\User\UserController;

Route::group(['prefix' => 'user', 'middleware' => 'user'], function () {
    Route::get('home', [UserController::class, 'home'])->name('userHome');
    Route::get('product/detail/{id}', [UserController::class, 'productDetail'])->name('user#ProductDetail');
    Route::post('comment', [UserController::class, 'comment'])->name('user#Comment');
    Route::get('comment/delete/{id}', [UserController::class, 'delete'])->name('user#DeleteComment');
    Route::get('profile/edit', [UserController::class, 'edit'])->name('user#EditProfile');
    Route::post('profile/update', [UserController::class, 'update'])->name('user#UpdateProfile');
    Route::get('password/change', [UserController::class, 'changePassword'])->name('user#ChangePassword');
    Route::post('password/change', [UserController::class, 'changeProcess'])->name('user#ChangeProcess');
    Route::post('rating', [UserController::class, 'rating'])->name('user#Rating');
    Route::get('contact', [UserController::class, 'contact'])->name('user#Contact');
    Route::post('contact/Message', [UserController::class, 'contactMessage'])->name('user#ContactMessage');
    Route::get('cart', [UserController::class, 'cartPage'])->name('user#Cart');
    Route::post('addToCart', [UserController::class, 'addToCart'])->name('user#AddToCart');
    Route::get('cartDelete', [UserController::class, 'cartDelete'])->name('user#CartDelete');
    Route::get('tempStorage', [UserController::class, 'tempStorage'])->name('user#tempStorage');
    Route::get('paymentPage', [UserController::class, 'paymentPage'])->name('user#paymentPage');
    Route::post('order', [UserController::class, 'order'])->name('user#order');
    Route::get('orderList', [UserController::class, 'orderList'])->name('user#OrderList');
});
