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

    Route::delete('revoke-token', [AuthenticationController::class, 'revokeToken']);

    Route::post('user/push-token', [AuthenticationController::class, 'updatePushToken']);
    Route::delete('user/push-token', [AuthenticationController::class, 'clearPushToken']);

    Route::get('categories', [CategoryController::class, 'getCategories']);
    Route::get('categories/{id}', [CategoryController::class, 'getCategory']);
    Route::get('categories/{categoryId}/books', [BookController::class, 'getBooks']);

    Route::get('books', [BookController::class, 'search']);
    Route::get('books/{bookId}', [BookController::class, 'getBook']);
    Route::post('books/{bookId}/request', [BookController::class, 'requestBorrow']);
    Route::post('books/{bookId}/notify', [BookController::class, 'notify']);

    Route::get('histories', [HistoryController::class, 'getHistories']);
    Route::get('histories/overview', [HistoryController::class, 'getOverview']);
    Route::get('histories/{id}', [HistoryController::class, 'getHistory']);
});