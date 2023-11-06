<?php

use App\Http\Controllers\Caregiver\Auth\ForgotPasswordController;
use App\Http\Controllers\Caregiver\Auth\LoginController;
use App\Http\Controllers\Caregiver\Auth\LogOutController;
use App\Http\Controllers\Caregiver\Auth\SignUpController;
use App\Http\Controllers\Caregiver\Bidding\BiddingController;
use App\Http\Controllers\Caregiver\Document\DocumentUploadController;
use App\Http\Controllers\Caregiver\Job\AcceptJobController;
use App\Http\Controllers\Caregiver\Job\CompleteJobController;
use App\Http\Controllers\Caregiver\Job\GetBiddingResultsController;
use App\Http\Controllers\Caregiver\Job\JobController;
use App\Http\Controllers\Caregiver\Job\OngoingJobController;
use App\Http\Controllers\Caregiver\Job\QuickCallController;
use App\Http\Controllers\Caregiver\Job\SearchJobController;
use App\Http\Controllers\Caregiver\Job\StartJobController;
use App\Http\Controllers\Caregiver\Job\UpcomingController;
use App\Http\Controllers\Caregiver\Location\LocationController;
use App\Http\Controllers\Caregiver\MapLocation\MapLocationController;
use App\Http\Controllers\Caregiver\Profile\BasicProfileController;
use App\Http\Controllers\Caregiver\Rating\RatingController;
use App\Http\Controllers\Caregiver\Registration\ProfileRegistrationController;
use App\Http\Controllers\Caregiver\Status\StatusInformationController;
use App\Http\Controllers\Caregiver\Stripe\StripePaymentController;
use App\Http\Controllers\Chatting\ChattingController;
use Carbon\Carbon;
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

// Route::middleware(['throttle:limited-request'])->group(function () {
    Route::post('signup', [SignUpController::class, 'signUp']);
    Route::post('login', [LoginController::class, 'login']);
    Route::post('resend-otp', [SignUpController::class, 'resendOtp']);
    Route::post('verify-otp', [SignUpController::class, 'verifyOtp']);
    Route::get('return-url', [StripePaymentController::class, 'returnUrl'])->name('stripe.return.url');
    Route::group(['prefix' => 'forgot-password'], function(){
        Route::post('send-verify-email-link', [ForgotPasswordController::class, 'sendVerifyEmailLink']);
        Route::post('verify-otp', [ForgotPasswordController::class, 'verifyOTP']);
        Route::post('create-new-password', [ForgotPasswordController::class, 'createNewPassword']);
    });
// });



Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::group(['prefix' => 'user'], function(){
        Route::post('update-location', [LocationController::class, 'updateCurrentLocation']);
    });

    Route::group(['prefix' => 'profile'], function(){

        Route::get('get-details', [BasicProfileController::class, 'getDetails']);

        Route::group(['prefix' => 'registration'], function(){
            Route::post('basic-information', [ProfileRegistrationController::class, 'basicInformation']);
            Route::post('optional-information', [ProfileRegistrationController::class, 'optionalinformation']);
            Route::get('get-registration-details', [ProfileRegistrationController::class, 'getRegistrationDetails']);
        });

        Route::post('edit-phone-or-experience', [BasicProfileController::class, 'editPhoneOrExperience']);

        Route::group(['prefix' => 'bio'], function(){
            Route::post('add', [BasicProfileController::class, 'addBio']);
        });

        Route::group(['prefix' => 'education'],function(){
            Route::post('add', [BasicProfileController::class, 'addEducation']);
            Route::post('edit', [BasicProfileController::class, 'editEducation']);
        });

        Route::group(['prefix' => 'certificate'],function(){
            Route::post('add', [BasicProfileController::class, 'addCertificate']);
            Route::post('edit', [BasicProfileController::class, 'editCertificate']);
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

        Route::group(['prefix' => 'quick-call'], function(){
            Route::get('get', [QuickCallController::class,'getQuickCallJobs']);
        });

        Route::group(['prefix' => 'accept-job'], function(){
            Route::post('accept', [AcceptJobController::class, 'acceptJob']);
        });

        Route::group(['prefix' => 'upcoming'], function(){
            Route::get('get', [UpcomingController::class, 'getUpcomingJob']);
        });
        
        Route::group(['prefix' => 'ongoing'], function(){
            Route::get('get', [OngoingJobController::class, 'ongoingJob']);
        });
       
        Route::group(['prefix' => 'start-job'], function(){
            Route::post('start', [StartJobController::class, 'startJob']);

        });

        Route::group(['prefix' => 'complete-job'], function(){
            Route::post('complete', [CompleteJobController::class, 'completeJob']);
            Route::get('get', [CompleteJobController::class, 'getCompleteJob']);
            Route::get('details', [CompleteJobController::class, 'getCompleteJobDetails']);
        });

        Route::group(['prefix' => 'bidding'], function(){
            Route::post('submit-bid', [BiddingController::class, 'submitBid']);
            Route::get('get-results', [GetBiddingResultsController::class, 'getBiddingResult']);
        });

        Route::post('search', [SearchJobController::class, 'search']);


        Route::get('agency-profile', [JobController::class, 'getAgencyProfile']);
    });

    Route::group(['prefix' => 'document'], function(){
        Route::post('upload', [DocumentUploadController::class, 'uploadDocument']);
        Route::get('get', [DocumentUploadController::class, 'getDocument']);
        Route::post('delete', [DocumentUploadController::class, 'deleteDocument']);
        Route::post('update-status', [DocumentUploadController::class, 'updateStatus']);
    });

    Route::group(['prefix' => 'rating'], function(){
        Route::get('get', [RatingController::class, 'getCaregiverRating']);
        Route::post('add-agency-rating', [RatingController::class, 'addAgencyRating']);
    });


    Route::group(['prefix' => 'map-locations'], function(){
        Route::get('get-locations', [MapLocationController::class, 'getJobLocations']);
    });

    Route::group(['prefix' => 'chatting'], function(){
        Route::post('upload-image',[ChattingController::class, 'uploadImage']);
        Route::post('upload-message',[ChattingController::class, 'uploadMessage']);
        Route::post('update-message',[ChattingController::class, 'updateMessage']);
        Route::get('get-chats',[ChattingController::class, 'getChats']);
    });

    Route::group(['prefix' => 'stripe'], function(){
        Route::post('create-connected-account', [StripePaymentController::class, 'createConnectedAccount']);
        Route::get('check-connected-account-status', [StripePaymentController::class, 'checkConnectedAccountStatus']);
        Route::get('refresh-url', [StripePaymentController::class, 'refreshUrl'])->name('stripe.refresh.url');
        Route::get('get-all-connected-accounts', [StripePaymentController::class, 'getAccounts']);
        Route::get('get-my-account-details', [StripePaymentController::class, 'getMyAccountDetails']);
        Route::post('delete-accounts', [StripePaymentController::class, 'deleteAccount']);
    });


    Route::post('logout', [LogOutController::class, 'logout']);
});

Route::post('check-notification', function(){
   

    $token = 'eFzpDxSrQtO0IY9ZqJ3Z3_:APA91bHoEBGqPtshiLp23Q9vneYRElws6OSpGB4o0-ThmWasGVXuc_vX6zV2Vqpd6f8jdkooFWcDwFgdkD4dECvMBO6vsj5TBM74Nh7LCteZzvIUXJ1ksqV0MjlzqQD84KIt9M1V_UM9';


    $server_key = env('FIREBASE_SERVER_KEY');
            
    $msg = [
        'message'   => '',
    ];

    $notify_data = [
        'body' => 'WelcomE Back Bro',
        'title' => 'Peaceworc'
    ];

    $registrationIds = $token;
        
    // if(count($token) > 1){
    //     $fields = array
    //     (
    //         'registration_ids' => $registrationIds, //  for  multiple users
    //         'notification'  => $notify_data,
    //         'data'=> [],
    //         'priority'=> 'high'
    //     );
    // }
    // else{
        
        $fields = array
        (
            'to' => $registrationIds, //  for  only one users
            'notification'  => $notify_data,
            'data'=> [
                'message' => 'WelcomE back Fatele' 
            ],
            'priority'=> 'high'
        );
    // }
        
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key='. $server_key;

    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    $result = curl_exec($ch );
    if ($result === FALSE) 
    {
        die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close( $ch );
    return $result;

});

Route::get('time-and-zone',function(){
    // echo date('Y-m-d H:i A');
    // echo "\n ------------------- \n";
    // echo  date_default_timezone_get() ;

    // echo "\n ------------- Timezone Using Carbon----------- \n";

    // echo $timeZone = Carbon::now();
    // echo "\n ------------------- \n";
    // echo $timeZone->tzName;

     
});