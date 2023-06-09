<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/chat/{id?}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{id?}', [ChatController::class, 'store'])->name('chat.store');
    Route::put('/chat/{chat}', [ChatController::class, 'update'])->middleware('can:show-chat,chat')->name('chat.update');
    Route::delete('/chat/{chat}', [ChatController::class, 'destroy'])->middleware('can:show-chat,chat')->name('chat.delete');
});

require __DIR__.'/auth.php';
