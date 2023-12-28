<?php

use App\Http\Controllers\api\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Resources\ErrorResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['namespace' => 'api\v3', 'prefix' => 'v1'], function () {
    Route::post('register', [RegisterController::class,'register']);
    Route::post('login', [LoginController::class, 'login']);
    Route::group(['middleware' => 'jwt.verify'], function () {
        Route::get('user', [UserController::class, 'userInformation']);
        Route::get('logout', [UserController::class, 'logout']);
        Route::post('updateProfile', [UserController::class, 'updateProfile']);
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//default Error Route
Route::fallback(function () {
    return ErrorResource::make([
       'statusCode' => 404,
       'error' => 'Route not found'
    ]);
});
