<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\HistoryController;

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

Route::post('token', [AuthenticationController::class, 'token']);

Route::middleware('auth:sanctum')->group(function() {
    Route::get('whoami', function (Request $request) {
        return $request->user();
    });

    Route::post('user/push-token', [AuthenticationController::class, 'updatePushToken']);
    Route::delete('user/push-token', [AuthenticationController::class, 'clearPushToken']);

    Route::get('categories', [CategoryController::class, 'getCategories']);
    Route::get('categories/{id}', [CategoryController::class, 'getCategory']);
    Route::get('categories/{categoryId}/books', [BookController::class, 'getBooks']);
    Route::post('books/{bookId}/request', [BookController::class, 'requestBorrow']);

    Route::get('history', [HistoryController::class, 'getHistory']);
});