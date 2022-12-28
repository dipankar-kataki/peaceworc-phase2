<?php

use App\Http\Controllers\Caregiver\Auth\LoginController;
use App\Http\Controllers\Caregiver\Auth\LogOutController;
use App\Http\Controllers\Caregiver\Auth\SignUpController;
use App\Http\Controllers\Caregiver\Bidding\BiddingController;
use App\Http\Controllers\Caregiver\Job\JobController;
use App\Http\Controllers\Caregiver\Profile\BasicProfileController;
use App\Http\Controllers\Caregiver\Registration\ProfileRegistrationController;
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

Route::post('signup', [SignUpController::class, 'signUp']);
Route::post('login', [LoginController::class, 'login']);
Route::post('check-email-exist', [SignUpController::class, 'checkEmailExists']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::group(['prefix' => 'profile'], function(){
        Route::post('register', [ProfileRegistrationController::class, 'profileRegistration']);
        Route::post('change-password', [BasicProfileController::class, 'changePassword']);
    });

    

    Route::group(['prefix' => 'job'], function(){
        Route::get('get-jobs', [JobController::class, 'getJobs']);
        Route::get('get-bidded-jobs', [JobController::class, 'getBiddedJobs']);
        Route::get('get-single-job-for-bidded', [JobController::class, 'getSingleJobForBidding']);

        Route::get('quick-call', [JobController::class,'getQuickCallJobs']);

        Route::group(['prefix' => 'bidding'], function(){
            Route::post('submit-bid', [BiddingController::class, 'submitBid']);
        });
    });
    Route::post('logout', [LogOutController::class, 'logout']);
});