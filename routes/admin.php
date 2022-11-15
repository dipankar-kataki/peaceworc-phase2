<?php

use App\Http\Controllers\Admin\Agency\AgencyController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\LogOutController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
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

    Route::group([ 'prefix' => 'agency'], function(){
        Route::get('list', [AgencyController::class, 'getList'])->name('admin.get.agency.list');
    });
    Route::get('logout', [LogOutController::class, 'logout'])->name('admin.logout');
    
});
