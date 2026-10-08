<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CheckoutController;

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::post('/items', [ItemController::class, 'store'])->name('items.store');
Route::post('/items/ai-summary', [ItemController::class, 'generateAiSummary'])->name('items.ai_summary');
Route::post('/items/{item}/checkout', [CheckoutController::class, 'store'])->name('items.checkout');
Route::post('/items/{item}/return', [CheckoutController::class, 'update'])->name('items.return');