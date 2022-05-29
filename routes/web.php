<?php

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

    Route::get('users', function () {
        return view('admin.users.list');
    })->name('users');
    Route::get('users/list', [UserController::class, 'getClients'])->name('users.list');

    Route::get('products', function () {
        return view('admin.products.list');
    })->name('products');
    Route::get('products/list', [ProductController::class, 'getProducts'])->name('products.list');
    Route::get('products/create', [ProductController::class, 'formCreateProduct'])->name('products.create');
    Route::post('products/post', [ProductController::class, 'createProduct'])->name('products.post');
});
