<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SuplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TestUserController;
use App\Http\Controllers\MakerController;
use App\Http\Controllers\PhotoListController;
use App\Http\Controllers\PrefectureController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/suppliers', [SuplierController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/users', [TestUserController::class, 'index']);

Route::get('/search_test', [ProductController::class, 'searchTest']);
Route::get('/maker_test', [MakerController::class, 'index']);


Route::get('/prefectures', [PrefectureController::class, 'search']);
Route::get('/photos', [PhotoListController::class, 'search']);
