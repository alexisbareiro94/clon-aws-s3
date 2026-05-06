<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BucketController;
use App\Http\Controllers\DownloadEventController;
use App\Http\Controllers\ObjectController;
use App\Http\Controllers\SharedLinkController;
use App\Http\Controllers\UserSettingController;
use Illuminate\Support\Facades\Route;

Route::get('/shared/{token}', [SharedLinkController::class, 'processToken'])->name('shared.link.processToken');
Route::get('/shared/{token}/view', [SharedLinkController::class, 'viewFile'])->name('shared.link.viewFile');
Route::get('/download/{link}', [DownloadEventController::class, 'downloadShared'])->name('download.object')->middleware('throttle:5,1');
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginView'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login')->middleware('throttle:5,1');
    Route::get('/register', [AuthController::class, 'registerView'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register')->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('bucket.index');
    })->name('home');

    Route::post('/createToken', [AuthController::class, 'createToken'])->name('createToken');
    Route::delete('/revokeToken/{token}', [AuthController::class, 'revoke'])->name('revokeToken');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/settings', UserSettingController::class)->name('settings.index'); // view

    // Buckets
    Route::get('/buckets', [BucketController::class, 'index'])->name('bucket.index');
    Route::get('/buckets/create', [BucketController::class,  'create'])->name('bucket.create'); // view
    Route::post('/buckets', [BucketController::class, 'store'])->name('bucket.store');
    Route::get('/{bucket:slug}', [BucketController::class, 'show'])->name('bucket.show');
    Route::get('/buckets/{bucket:slug}/settings', [BucketController::class, 'settingsView'])->name('bucket.settings');
    Route::put('/buckets/{bucket}', [BucketController::class, 'update'])->name('bucket.update');
    Route::delete('/buckets/{bucket}', [BucketController::class, 'destroy'])->name('bucket.destroy');

    // Objects
    Route::get('/bucket/{bucket}/upload', [ObjectController::class, 'index'])->name('object.index'); // view
    Route::post('/bucket/{bucket}/upload', [ObjectController::class, 'store'])->name('object.store');
    Route::delete('/object/{objecto}', [ObjectController::class, 'destroy'])->name('object.destroy');
    Route::get('/{bucket:slug}/{objecto:original_name}', [ObjectController::class, 'show'])->name('object.show');
    Route::put('/update/{objecto:original_name}', [ObjectController::class, 'update'])->name('object.update');
    Route::get('/{bucket:slug}/{objecto:original_name}/view', [ObjectController::class, 'viewFile'])->name('object.view');

    Route::post('/createSharedLink/{objecto}', [SharedLinkController::class, 'store'])->name('shared.link.store')->middleware('throttle:10,1');
    Route::post('/rovoke/{link}', [SharedLinkController::class, 'revoke'])->name('shared.link.revoke');

    Route::get('/download/own/{objecto}', [DownloadEventController::class, 'downloadOwn'])->name('download.own');
});
