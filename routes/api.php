<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// signup and login

Route::post('login', [AuthController::class, 'signin'])->name('login');
Route::post('register', [AuthController::class, 'signup']);

// protected routes go here
Route::middleware('auth:sanctum')->group(function () {
    //Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::resource('blogs', BlogController::class);
});

// Route::group(['middleware' => ['auth:sanctum']], function () {
//     // protected routes go here
//     Route::get('/me', function (Request $request) {
//         return auth()->user();
//     });
//     Route::post('/auth/logout', [AuthController::class, 'logout']);

//     Route::resource('blogs', BlogController::class);
// });
