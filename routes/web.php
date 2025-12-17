<?php

use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/search-users', [SiteController::class, 'searchUsers'])->name('searchUsers');

Route::get('/auth-login', [SiteController::class, 'authLogin'])->name('authLogin');
Route::post('/create-lead', [SiteController::class, 'createLead'])->name('createLead');
Route::post('/create-chat-message', [SiteController::class, 'createChatMessage'])->name('createChatMessage');

