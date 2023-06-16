<?php

use App\Http\Controllers\ShipsController;
use App\Http\Controllers\UsersController;
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

// DEFAULT
Route::get('{any?}', function ($any = null) {
    return view('welcome');
})->where('any', '.*');

// GET
Route::get('/', function () {
    return view('welcome');
});

// POST

// user
Route::post('/loginuser', [UsersController::class, 'getUser']);
Route::post('/checkuser', [UsersController::class, 'checkUser']);

// ships
Route::post('/getships', [ShipsController::class, 'getships']);
Route::post('/addship', [ShipsController::class, 'addship']);

// Route::post('user/{id}', ShowProfile::class);
