<?php

use App\Http\Controllers\Agency\Auth\ForgotPasswordController;
use App\Http\Controllers\Agency\Auth\LoginController;
use App\Http\Controllers\Agency\Auth\LogOutController;
use App\Http\Controllers\Agency\Auth\SignUpController;
use App\Http\Controllers\Agency\AuthorizeOfficer\AuthorizeOfficerController;
use App\Http\Controllers\Agency\Job\Accepted\OngoingJobController;
use App\Http\Controllers\Agency\Job\AcceptedJob\JobOngoingController;
use App\Http\Controllers\Agency\Job\AcceptedJob\UpcomingJobController;
use App\Http\Controllers\Agency\Job\CareType\CareTypeController;
use App\Http\Controllers\Agency\Job\CompletedJob\CompletedJobController;
use App\Http\Controllers\Agency\Job\PostJobController;
use App\Http\Controllers\Agency\Owner\OwnerProfileController;
use App\Http\Controllers\Agency\Payments\AgencyPaymentController;
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

Route::middleware(['throttle:limited-request'])->group(function () {
    Route::post('signup', [SignUpController::class, 'signUp']);
    Route::post('login', [LoginController::class, 'login']);
    Route::post('resend-otp', [SignUpController::class, 'resendOtp']);
    Route::post('verify-otp', [SignUpController::class, 'verifyOtp']);
    Route::post('send-forgot-password-mail', [ForgotPasswordController::class, 'sendForgotPasswordMail']);
    
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::group(['prefix' => 'owner'], function(){
        Route::post('edit-phone', [OwnerProfileController::class, 'editPhone']);
        Route::post('change-password', [OwnerProfileController::class, 'changePassword']);
    });
    

    Route::group(['prefix' => 'business-profile'], function(){
        Route::post('add-business-info', [ProfileRegistrationController::class, 'addBusinessInfo']);
        Route::post('add-optional-info', [ProfileRegistrationController::class, 'addOptionalInfo']);
        Route::get('get-profile-details', [ProfileRegistrationController::class, 'getProfileDetails']);
        Route::post('edit-basic-profile-details', [ProfileRegistrationController::class, 'editBasicProfileDetails']);
    });

    Route::group(['prefix' => 'authorize-officer'], function(){
        Route::post('create-officer', [AuthorizeOfficerController::class, 'createAuthorizeOfficer']);
        Route::get('get-officer', [AuthorizeOfficerController::class, 'authorizeOfficer']);
        Route::get('delete-officer', [AuthorizeOfficerController::class, 'deleteAuthorizeOfficer']);
        Route::post('edit-officer', [AuthorizeOfficerController::class, 'editAuthorizeOfficer']);
        Route::post('update-status', [AuthorizeOfficerController::class, 'updateStatus']);
    });

    Route::group(['prefix' => 'information'], function(){
        Route::get('status', [InformationStatusController::class, 'informationStatus']);
    });
    

    Route::group(['prefix' => 'job'], function(){
        Route::post('create', [PostJobController::class, 'createJob']);
        Route::get('get-job', [PostJobController::class, 'getJob']);
        Route::get('get-single-job',[PostJobController::class, 'getSingleJob']);
        Route::get('delete-job', [PostJobController::class, 'deleteJob']);

        Route::group(['prefix' => 'care-types'], function(){
            Route::get('get', [CareTypeController::class, 'getCareTypes']);
        });

        Route::group(['prefix' => 'accepted-job'],function(){
            Route::get('ongoing', [JobOngoingController::class, 'getOngoingJob']);
            Route::get('upcoming', [UpcomingJobController::class, 'getUpcomingJob']);
        });

        Route::group(['prefix' => 'completed-job'], function(){
            Route::get('get', [CompletedJobController::class, 'getCompletedJob']);
        });
    });

    Route::group(['prefix' => 'payment'], function(){
        Route::post('update-status', [AgencyPaymentController::class, 'updateStatus']);
    });
    
    Route::post('logout', [LogOutController::class,'logout']);
});


