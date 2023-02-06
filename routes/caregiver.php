<?php

use App\Http\Controllers\Caregiver\Auth\LoginController;
use App\Http\Controllers\Caregiver\Auth\LogOutController;
use App\Http\Controllers\Caregiver\Auth\SignUpController;
use App\Http\Controllers\Caregiver\Bidding\BiddingController;
use App\Http\Controllers\Caregiver\Job\AcceptJobController;
use App\Http\Controllers\Caregiver\Job\GetBiddingResultsController;
use App\Http\Controllers\Caregiver\Job\JobController;
use App\Http\Controllers\Caregiver\Job\OngoingJobController;
use App\Http\Controllers\Caregiver\Job\QuickCallController;
use App\Http\Controllers\Caregiver\Location\LocationController;
use App\Http\Controllers\Caregiver\Profile\BasicProfileController;
use App\Http\Controllers\Caregiver\Registration\ProfileRegistrationController;
use App\Http\Controllers\Caregiver\Status\StatusInformationController;
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

Route::middleware(['throttle:limited-request'])->group(function () {
    Route::post('signup', [SignUpController::class, 'signUp']);
    Route::post('login', [LoginController::class, 'login']);
    Route::post('check-email-exist', [SignUpController::class, 'checkEmailExists']);
});



Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::group(['prefix' => 'user'], function(){
        Route::post('update-location', [LocationController::class, 'updateCurrentLocation']);
    });

    Route::group(['prefix' => 'profile'], function(){

        Route::get('get-details', [BasicProfileController::class, 'getDetails']);

        Route::group(['prefix' => 'registration'], function(){
            Route::post('basic-information', [ProfileRegistrationController::class, 'basicinformation']);
            Route::post('optional-information', [ProfileRegistrationController::class, 'optionalinformation']);
        });

        Route::group(['prefix' => 'bio'], function(){
            Route::post('add', [BasicProfileController::class, 'addBio']);
        });

        Route::group(['prefix' => 'education'],function(){
            Route::post('add', [BasicProfileController::class, 'addEducation']);
        });

        Route::group(['prefix' => 'certificate'],function(){
            Route::post('add', [BasicProfileController::class, 'addCertificate']);
        });

        Route::post('change-photo', [BasicProfileController::class, 'changePhoto']);

        Route::post('change-password', [BasicProfileController::class, 'changePassword']);
    });


    Route::group(['prefix' => 'status'], function(){
        Route::get('profile-completion-status', [StatusInformationController::class, 'profileCompletionStatus']);
    });

    

    Route::group(['prefix' => 'job'], function(){
        Route::get('get-jobs', [JobController::class, 'getJobs']);
        Route::get('get-bidded-jobs', [JobController::class, 'getBiddedJobs']);
        Route::get('get-single-job-for-bidded', [JobController::class, 'getSingleJobForBidding']);
        Route::get('get-all-my-bidded-jobs', [JobController::class, 'getAllMyBiddedJobs']);

        Route::get('quick-call', [QuickCallController::class,'getQuickCallJobs']);

        Route::post('accept', [AcceptJobController::class, 'acceptJob']);
        Route::get('ongoing-job', [OngoingJobController::class, 'ongoingJob']);

        Route::group(['prefix' => 'bidding'], function(){
            Route::post('submit-bid', [BiddingController::class, 'submitBid']);
            Route::get('get-results', [GetBiddingResultsController::class, 'getBiddingResult']);
        });
    });
    Route::post('logout', [LogOutController::class, 'logout']);
});