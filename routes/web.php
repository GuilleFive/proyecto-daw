<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

    Auth::routes(['verify' => true]);

    Route::get('/', function () {
        return redirect()->route('home');
    });

    Route::get('home', function () {
        return view('home');
    })->name('home');


Route::group(['middleware' => ['can:create_products', 'verified', 'auth']], function () {

    Route::get('users', [UserController::class, 'getDatatable'])->name('users');
    Route::get('users/list', [UserController::class, 'getUsers'])->name('users.list');
    Route::delete('users/delete/{user}', [UserController::class, 'deleteUser'])->name('users.delete');

    Route::get('products', function () {
        return view('admin.products.list');
    })->name('products');
    Route::get('products/list', [ProductController::class, 'getProducts'])->name('products.list');
    Route::get('products/create', [ProductController::class, 'formCreateProduct'])->name('products.create');
    Route::get('products/edit/{product}', [ProductController::class, 'formEditProduct'])->name('products.edit');
    Route::post('products/post', [ProductController::class, 'createProduct'])->name('products.post');
    Route::post('products/change', [ProductController::class, 'editProduct'])->name('products.change');
    Route::delete('products/delete/{product}', [ProductController::class, 'deleteProduct'])->name('products.delete');

    Route::get('orders', function () {
        return view('admin.orders.list');
    })->name('orders');
    Route::get('orders/list', [OrderController::class, 'getOrders'])->name('orders.list');

});
