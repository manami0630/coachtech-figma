<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\OrderController;
//use App\Http\Controllers\AuthController;

<<<<<<< Updated upstream

Route::get('/attestation', [ItemController::class, 'attestation']);
=======
//Route::post('/register', [AuthController::class, 'register'])->name('register');
>>>>>>> Stashed changes

Route::get('/', [ItemController::class, 'index'])->name('items.index');

Route::get('/item/{id}', [ItemController::class, 'details']);

Route::put('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

Route::get('/items/search', [ItemController::class, 'search'])->name('items.search');

Route::get('/search-autocomplete', [ItemController::class, 'searchAutocomplete'])->name('search-autocomplete');

Route::get('/item/{id}', [ItemController::class, 'show'])->name('item.show');

Route::middleware('auth')->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

    Route::get('/purchase/address/{id}', [AddressController::class, 'change'])->name('change');

    Route::post('/purchase/address/update', [AddressController::class, 'update'])->name('address.update');

    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    Route::get('/mypage', [ProfileController::class, 'profile'])->name('profile.items');

    Route::post('/product/upload', [ItemController::class, 'store'])->name('product.store');

    Route::post('/purchase/{id}', [ItemController::class, 'purchase'])->name('purchase');

    Route::get('/sell', [ItemController::class, 'exhibit'])->name('exhibit');

    Route::post('/likes/toggle', [LikeController::class, 'toggle'])->name('likes.toggle');
<<<<<<< Updated upstream
=======

    Route::post('/orders/create-checkout-session', [OrderController::class, 'createStripeSession'])->name('orders.createStripeSession');

    Route::get('/orders/success', [OrderController::class, 'success'])->name('orders.success');

    Route::get('/orders/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
>>>>>>> Stashed changes
});

