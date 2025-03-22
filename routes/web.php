<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // video-haritasi => View | video => View
    Route::get('/video-haritasi', [VideoController::class, 'globe'])->name('video.globe');
    Route::get('/video-listesi', [VideoController::class, 'list'])->name('video.list');

    // video-create => View | video-upload => Controller
    Route::get('/video-create', [VideoController::class, 'create'])->name('video.create');
    Route::post('/video-upload', [VideoController::class, 'store'])->name('video.upload');

    // video/{id}/edit => View | video/{id} => Controller | video/{id}/delete => Controller
    Route::get('/video/{id}/edit', [VideoController::class, 'edit'])->name('video.edit');
    Route::put('/video/{id}', [VideoController::class, 'update'])->name('video.update');
    Route::delete('/video/{id}/delete', [VideoController::class, 'delete'])->name('video.delete');

    // video-detay/{country}/{city} => View
    Route::get('/video-detay/{country}/{city}', [VideoController::class, 'detail'])->name('video.detail');
});

Route::get('/test', [VideoController::class, 'test'])->name('video.test');

require __DIR__ . '/auth.php';
