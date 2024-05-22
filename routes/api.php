<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommandeController;
use App\Http\Controllers\Api\CommandePlatRestaurantStatutController;
use App\Http\Controllers\Api\PlatController;
use App\Http\Controllers\Api\RestaurantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    Route::prefix('users')
        ->controller(AuthController::class)
        ->group(function () {
            Route::get('/', 'index');
            Route::get('/profile', 'profile');
            // Route::get('count/{user}', 'countCmdesUser');
            Route::get('/get_resto', 'getResto');
            Route::post('/login', 'login');
            Route::post('/register', 'register');
            Route::put('/update', 'update');
            // Route::put('/{user}', 'update');
            Route::post('/change-password', 'changePassword');
            Route::post('/logout', 'logout');
            Route::get('/{token}', 'verifyEmail');
            Route::post('/forgot-password', 'forgotPassword');
            Route::get('/reset-password/{token}', 'resetPassword');
        });

    Route::prefix('restaurants')
        ->controller(RestaurantController::class)
        ->group(function () {
            Route::get('/', 'index');
            Route::get('/{restaurant}', 'show');
            Route::post('/create', 'store');
            Route::put('/{restaurant}', 'update');
            Route::delete('/{restaurant}', 'delete');
        });

    Route::prefix('plats')
        ->controller(PlatController::class)
        ->group(function () {
            Route::get('/', 'index');
            Route::get('/{plat}', 'show');
            Route::post('/create', 'store');
            Route::put('/{plat}', 'update');
            Route::delete('/{plat}', 'delete');
        });

    Route::prefix('commandes')
        ->controller(CommandeController::class)
        ->group(function () {
            Route::get('/', 'index');
            Route::get('/{commande}', 'show');
            Route::post('/create', 'store');
            Route::put('/{commande}', 'update');
            Route::delete('/{commande}', 'delete');
        });

    Route::prefix('lignecommandes')
        ->controller(CommandePlatRestaurantStatutController::class)
        ->group(function () {
            Route::get('/', 'index');
            Route::get('/{lignecommande}', 'show');
            Route::post('/create', 'store');
            Route::put('/{lignecommande}', 'update');
            Route::delete('/{lignecommande}', 'delete');
        });
});
