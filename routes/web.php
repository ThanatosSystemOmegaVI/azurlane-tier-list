<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

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
Route::post('/loginuser', [UsersController::class, 'getUser']);
Route::post('/checkuser', [UsersController::class, 'checkUser']);

// Route::post('user/{id}', ShowProfile::class);