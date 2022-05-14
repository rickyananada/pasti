<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Office\AuthController;
use App\Http\Controllers\Office\DashboardController;
use App\Http\Controllers\Office\ProductController;
use App\Providers\RouteServiceProvider as ProvidersRouteServiceProvider;

Route::group(['domain' => ''], function() {
    Route::prefix('office/')->name('office.')->group(function(){
        Route::get('auth',[AuthController::class, 'index'])->name('auth.index');
        Route::prefix('auth')->name('auth.')->group(function(){
            Route::post('login',[AuthController::class, 'do_login'])->name('login');
            Route::post('register',[AuthController::class, 'do_register'])->name('register');
        });

        Route::middleware(['auth:office'])->group(function(){
        //     Route::get('verification',[AuthController::class, 'verification'])->name('auth.verification');
        //     Route::post('verify/{auth:email}',[AuthController::class, 'do_verify'])->name('auth.verify');
            Route::get('logout',[AuthController::class, 'do_logout'])->name('auth.logout');
        //     Route::post('order/pdf', [\App\Http\Controllers\Office\OrderController::class, 'pdf'])->name('order.pdf');
        //     Route::get('order/{order}/invoice', [\App\Http\Controllers\Office\OrderController::class, 'invoice'])->name('order.invoice');
        }); 

        Route::group(['middleware' => ['auth:office']], function () {
            Route::redirect('/', ProvidersRouteServiceProvider::DASHBOARD, 301);
            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
            // Route::resource('banner', BannerC::class);
            // Route::resource('category', CategoryC::class);
            Route::resource('product', ProductController::class);
            // Route::patch('recomendation/{id}',[RecomendedController::class,'recomendation','__invoke'])->name('product.recomendation');
            // Route::patch('unrecomendation/{id}',[RecomendedController::class,'unrecomendation','__invoke'])->name('product.unrecomendation');
            // Route::resource('customer', CustomerC::class);
            // Route::get('customer/pdf', [\App\Http\Controllers\Office\CustomerController::class, 'pdf'])->name('customer.pdf');
            // Route::resource('employee', EmployeeC::class);
            // Route::resource('order', OrderC::class);
            // Route::get('order/{order}/download', [\App\Http\Controllers\Office\OrderController::class, 'download'])->name('order.download');
            // Route::patch('order/{order}/reject', [\App\Http\Controllers\Office\OrderController::class, 'reject'])->name('order.reject');
            // Route::patch('order/{order}/acc', [\App\Http\Controllers\Office\OrderController::class, 'acc'])->name('order.acc');
        });
    });
});
