<?php

use App\Http\Controllers\Agency\Auth\ForgotPasswordController;
use App\Http\Controllers\Agency\Auth\LoginController;
use App\Http\Controllers\Agency\Auth\LogOutController;
use App\Http\Controllers\Agency\Auth\SignUpController;
use App\Http\Controllers\Agency\AuthorizeOfficer\AuthorizeOfficerController;
use App\Http\Controllers\Agency\Job\PostJobController;
use App\Http\Controllers\Agency\Registration\ProfileRegistrationController;
use App\Http\Controllers\Agency\Status\InformationStatusController;
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
Route::post('send-forgot-password-mail', [ForgotPasswordController::class, 'sendForgotPasswordMail']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::group(['prefix' => 'profile'], function(){
        Route::post('registration', [ProfileRegistrationController::class, 'profileRegistration']);
        Route::post('edit-registration', [ProfileRegistrationController::class, 'editProfileRegistration']);
    });

    Route::group(['prefix' => 'authorize-officer'], function(){
        Route::post('create-officer', [AuthorizeOfficerController::class, 'createAuthorizeOfficer']);
        Route::get('get-officer', [AuthorizeOfficerController::class, 'authorizeOfficer']);
    });

    Route::group(['prefix' => 'information'], function(){
        Route::get('status', [InformationStatusController::class, 'informationStatus']);
    });

    Route::group(['prefix' => 'job'], function(){
        Route::post('create', [PostJobController::class, 'createJob']);
        Route::get('get-job', [PostJobController::class, 'getJob']);
    });
    
    Route::post('logout', [LogOutController::class,'logout']);
});