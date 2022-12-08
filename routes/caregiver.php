<?php

use App\Http\Controllers\Caregiver\Auth\LoginController;
use App\Http\Controllers\Caregiver\Auth\LogOutController;
use App\Http\Controllers\Caregiver\Auth\SignUpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CAREGIVER API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('sign-up', [SignUpController::class, 'signUp']);
Route::post('login', [LoginController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [LogOutController::class, 'logout']);
});