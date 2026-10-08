<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CheckoutController;

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::post('/items/{item}/checkout', [CheckoutController::class, 'store'])->name('items.checkout');
Route::post('/items/{item}/return', [CheckoutController::class, 'update'])->name('items.return');