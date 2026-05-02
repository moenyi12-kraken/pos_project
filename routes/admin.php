<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SuperAdminController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['admin']], function () {
    Route::get('adminHome', [AdminController::class, 'home'])->name('adminHome');

    Route::group(['prefix' => 'category'], function () {
        Route::get('list', [CategoryController::class, 'list'])->name('admin#Category');
        Route::post('create', [CategoryController::class, 'create'])->name('category#Create');
        Route::get('delete/{id}', [CategoryController::class, 'delete'])->name('category#Delete');
        Route::get('edit/{id}', [CategoryController::class, 'edit'])->name('category#Edit');
        Route::post('update/{id}', [CategoryController::class, 'update'])->name('category#Update');
    });

    Route::group(['prefix' => 'product'], function () {
        Route::get('page', [ProductController::class, 'productPage'])->name('admin#ProductPage');
        Route::post('create', [ProductController::class, 'create'])->name('product#Create');
        Route::get('list/{action?}', [ProductController::class, 'list'])->name('product#List');
        Route::get('delete/{id}', [ProductController::class, 'delete'])->name('product#Delete');
        Route::get('edit/{id}', [ProductController::class, 'edit'])->name('product#Edit');
        Route::post('update', [ProductController::class, 'update'])->name('product#Update');
        Route::get('details/{id}', [ProductController::class, 'detail'])->name('product#Detail');
    });

    Route::get('changePasswordPage', [ProfileController::class, 'changePasswordPage'])->name('password#Change');
    Route::post('changePassword', [ProfileController::class, 'changePassword'])->name('change#Password');
    Route::get('editProfilePage', [ProfileController::class, 'editProfilePage'])->name('profile#Edit');
    Route::post('editProfile', [ProfileController::class, 'editProfile'])->name('edit#Profile');

    Route::group(['middleware' => 'superadmin'], function () {
        Route::get('addAdmin', [SuperAdminController::class, 'addAdmin'])->name('admin#Add');
        Route::post('createAdmin', [SuperAdminController::class, 'createAdmin'])->name('admin#Create');
        Route::get('deleteAdmin/{id}', [SuperAdminController::class, 'deleteAdmin'])->name('admin#Delete');
        Route::get('adminList', [SuperAdminController::class, 'adminList'])->name('admin#List');
        Route::get('userList', [SuperAdminController::class, 'userList'])->name('user#List');
        Route::get('payment/{action?}', [SuperAdminController::class, 'payment'])->name('super#Payment');
        Route::post('create', [SuperAdminController::class, 'createpayment'])->name('payment#Create');
        Route::get('edit/{id}', [SuperAdminController::class, 'edit'])->name('payment#Edit');
        Route::get('paymentDelete/{id}', [SuperAdminController::class, 'delete'])->name('payment#Delete');
        Route::post('update', [SuperAdminController::class, 'update'])->name('payment#Update');
    });

    Route::group(['prefix' => 'order', 'middleaware' => 'admin'], function () {
        Route::get('orderBoard', [OrderController::class, 'orderBoard'])->name('admin#OrderBoard');
        Route::get('orderListDetail/{orderCode}', [OrderController::class, 'orderListDetail'])->name('admin#OrderListDetail');
        Route::get('reject', [OrderController::class, 'rejectProcess'])->name('admin#OrderReject');
        Route::get('access', [OrderController::class, 'accessProcess'])->name('admin#OrderAccess');
        Route::get('statusChange', [OrderController::class, 'statusChange'])->name('admin#StatusChange');
        Route::get('saleInfo', [OrderController::class, 'saleInformation'])->name('admin#SaleInfo');
    });
});
