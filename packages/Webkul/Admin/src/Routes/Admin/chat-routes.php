<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Chat\ChatController;

Route::group(['middleware' => ['user'], 'prefix' => config('app.admin_path')], function () {

    Route::controller(ChatController::class)->prefix('chat')->group(function () {

        Route::get('', 'index')->name('admin.chat.index');

        Route::get('support', 'chatSupport')->name('user.chat.support');
        Route::get('start/{id}', 'initChat')->name('user.chat.init');

        Route::get('search', 'search')->name('user.chat.search');

        Route::get('v/{uuid}', 'chatOpen')->name('user.chat.open');
        Route::get('messages/{uuid}', 'chatMessages')->name('user.chat.messages');
        Route::post('messages/{uuid}', 'sendChatMessages')->name('user.chat.send');
        Route::get('read/{uuid}', 'markChatRead')->name('user.chat.read');

    });

});
