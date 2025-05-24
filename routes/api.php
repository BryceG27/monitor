<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/products')->group(function() {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/{product}/show', [ProductController::class, 'show'])->name('products.show');
    Route::post('/', [ProductController::class, 'store'])->name('products.store');
    Route::patch('/{product}/update', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/', [ProductController::class, 'destroy'])->name('products.destroy');
});

Route::prefix('/orders')->group(function() {
    Route::get('/', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/{order}/show', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/', [OrderController::class, 'store'])->name('orders.store');
    Route::patch('/{order}/update', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/', [OrderController::class, 'destroy'])->name('orders.destroy');
});
