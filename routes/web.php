<?php

use App\Http\Controllers\Site\SiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Landing Page Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('site.index');
})->name('web.landing');

Route::get('admin', function () {
    return view('admin.auth.login');
})->name('web.login');

Route::get('', [SiteController::class, 'index'])->name('site.index');
Route::post('contact', [SiteController::class, 'contact'])->name('site.contact');
Route::get('terms-and-conditions', [SiteController::class, 'terms'])->name('site.terms');
Route::get('privacy-policy', [SiteController::class, 'privacy'])->name('site.privacy');