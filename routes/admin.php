<?php

use App\Http\Controllers\Admin\Agency\Access\AccessController;
use App\Http\Controllers\Admin\Agency\AgencyController;
use App\Http\Controllers\Admin\Agency\Job\JobController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\LogOutController;
use App\Http\Controllers\Admin\AuthorizeOfficer\AuthorizeOfficerController;
use App\Http\Controllers\Admin\Caregiver\CaregiverController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ADMIN Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('login', [LoginController::class, 'login'])->name('admin.login');

Route::group(['middleware' => 'auth'] ,function(){

    Route::get('dashboard', [DashboardController::class, 'viewDashboard'])->name('admin.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Agency Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register Agency routes for the application.
    |
    */


    Route::group([ 'prefix' => 'agency'], function(){
        Route::get('list', [AgencyController::class, 'getList'])->name('admin.get.agency.list');
        Route::get('details/{id}', [AgencyController::class, 'getAgencyDetails'])->name('admin.get.agency.details');
        Route::get('profile', [AgencyController::class, 'profile'])->name('admin.get.agency.profile');

        Route::group(['prefix' => 'access'], function(){
            Route::post('update-status', [AccessController::class, 'updateStatus'])->name('agency.access.update.status');
        });

        Route::group(['prefix' => 'authorize-officer'], function(){
            Route::get('list/{id?}', [AuthorizeOfficerController::class, 'getList'])->name('admin.get.authorize.officer.list');
        });

        Route::group(['prefix' => 'job'], function(){
            Route::get('list/{id?}', [JobController::class, 'getAllJobs'])->name('admin.agency.get.all.job');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Caregiver Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register Caregiver routes for the application.
    |
    */


    Route::group(['prefix' => 'caregiver'], function(){
        Route::get('list', [CaregiverController::class, 'getList'])->name('admin.get.caregiver.list');
        Route::get('details/{id}', [CaregiverController::class, 'getCaregiverDetails'])->name('admin.get.caregiver.details');
    });


    Route::get('logout', [LogOutController::class, 'logout'])->name('admin.logout');
    
});


Route::get('/login-expire',function(){
    return response()->json([
        'status' => 'error',
        'message' => 'Login expired. Please re-login.',
        'data' => null,
        'token' => 'null',
        'http_status_code' => 401
    ]);
})->name('login-expire');

Route::get('cache-route', function(){
    Artisan::call('php artisan route:cache');
    echo 'Routes Cached';
});

Route::get('optimize', function(){
    Artisan::call('php artisan optimize:clear');
    echo 'Optimized';
});