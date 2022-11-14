<?php

use App\Http\Controllers\Agency\Auth\LoginController;
use App\Http\Controllers\Agency\Auth\LogOutController;
use App\Http\Controllers\Agency\Auth\SignUpController;
use App\Http\Controllers\Agency\Registration\ProfileRegistrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AGENCY API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('signup', [SignUpController::class, 'signUp']);
Route::post('login', [LoginController::class, 'login']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('profile-registration', [ProfileRegistrationController::class, 'profileRegistration']);
    Route::post('logout', [LogOutController::class,'logout']);
});