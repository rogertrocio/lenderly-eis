<?php

use App\Enums\Action;
use App\Enums\Module;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentications
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::post('/check-in', [AuthController::class, 'checkIn'])->name('check-in');
Route::post('/check-out', [AuthController::class, 'checkOut'])->name('check-out');

/*
|--------------------------------------------------------------------------
| Modules
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/users/export/{type}', [UserController::class, 'export'])
        ->name('users.report')
        ->middleware(sprintf("permission:%s.%s", Module::USER, Action::ACCESS));

    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index')
        ->middleware(sprintf("permission:%s.%s", Module::USER, Action::ACCESS));

    Route::get('/users/{user}', [UserController::class, 'show'])
        ->name('users.show')
        ->middleware(sprintf("permission:%s.%s", Module::USER, Action::VIEW));

    Route::post('/users/{user}', [UserController::class, 'update'])
        ->name('users.update')
        ->middleware(sprintf("permission:%s.%s", Module::USER, Action::UPDATE));

    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy')
        ->middleware(sprintf("permission:%s.%s", Module::USER, Action::DELETE));

    Route::prefix('common')->group(function () {
        Route::get('/roles', [CommonController::class, 'getRoles'])->name('common.roles');
    });

    Route::get('/{any}', function () {
        return view('app');
    })->where('any', '.*');
});
