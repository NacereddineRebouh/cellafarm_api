<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::get('test', [UserController::class, 'test']);
Route::get('test2', [ProductsController::class, 'test']);

Route::controller(ProductsController::class)->group(function () {
    Route::post('Products/addProduct', 'create');
    Route::get('Products/list', 'List');
    Route::delete('Products/delete/{id}', 'Delete');
    Route::get('Products/getProduct/{id}', 'getProduct');
    Route::post('Products/updateProduct/{id}', 'update');
    Route::get('Products/search/{searchValue?}/{category?}', 'searchWithCategory');
});
