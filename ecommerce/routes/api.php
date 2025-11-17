<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

// Product routes
Route::get('/products', [ProductController::class, 'index']);

// Cart routes
Route::post('/cart', [CartController::class, 'store']);
Route::put('/cart/{id}', [CartController::class, 'update']);
Route::patch('/cart/{id}', [CartController::class, 'patch']);
Route::delete('/cart/{id}', [CartController::class, 'destroy']);

// Order routes
Route::post('/orders', [OrderController::class, 'store']);