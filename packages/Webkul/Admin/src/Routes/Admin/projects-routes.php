<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Projects\ProjectCategoryController;
use Webkul\Admin\Http\Controllers\Projects\ProjectController;
use Webkul\Admin\Http\Controllers\Projects\ProjectTaskController;

Route::group(['middleware' => ['user'], 'prefix' => config('app.admin_path')], function () {
    Route::controller(ProjectController::class)->prefix('projects')->group(function () {
        Route::get('', 'index')->name('admin.projects.index');

        Route::get('create', 'create')->name('admin.projects.create');

        Route::post('create', 'store')->name('admin.projects.store');

        Route::get('view/{id}', 'view')->name('admin.projects.view');

        Route::get('edit/{id}', 'edit')->name('admin.projects.edit');

        Route::put('edit/{id}', 'update')->name('admin.projects.update');

        Route::get('search', 'search')->name('admin.projects.search');

        Route::delete('{id}', 'destroy')->name('admin.projects.delete');

        Route::post('mass-destroy', 'massDestroy')->name('admin.projects.mass_delete');

    });
});

Route::group(['middleware' => ['user'], 'prefix' => config('app.admin_path')], function () {
    Route::controller(ProjectTaskController::class)->prefix('projects/tasks')->group(function () {
        Route::get('', 'index')->name('admin.projects.tasks.index');

        Route::get('create', 'create')->name('admin.projects.tasks.create');

        Route::post('create', 'store')->name('admin.projects.tasks.store');

        Route::get('view/{id}', 'view')->name('admin.projects.tasks.view');

        Route::get('edit/{id}', 'edit')->name('admin.projects.tasks.edit');

        Route::put('edit/{id}', 'update')->name('admin.projects.tasks.update');

        Route::get('search', 'search')->name('admin.projects.tasks.search');

        Route::delete('{id}', 'destroy')->name('admin.projects.tasks.delete');

        Route::post('mass-destroy', 'massDestroy')->name('admin.projects.tasks.mass_delete');

    });
});

Route::group(['middleware' => ['user'], 'prefix' => config('app.admin_path')], function () {
    Route::controller(ProjectCategoryController::class)->prefix('projects/categories')->group(function () {
        Route::get('', 'index')->name('admin.projects.categories.index');

        Route::get('create', 'create')->name('admin.projects.categories.create');

        Route::post('create', 'store')->name('admin.projects.categories.store');

        Route::get('view/{id}', 'view')->name('admin.projects.categories.view');

        Route::get('edit/{id}', 'edit')->name('admin.projects.categories.edit');

        Route::put('edit/{id}', 'update')->name('admin.projects.categories.update');

        Route::get('search', 'search')->name('admin.projects.categories.search');

        Route::delete('{id}', 'destroy')->name('admin.projects.categories.delete');

        Route::post('mass-destroy', 'massDestroy')->name('admin.projects.categories.mass_delete');

    });
});
