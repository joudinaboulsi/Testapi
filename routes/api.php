<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertificatesController;


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


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('me', 'me');
    Route::post('user-approve/{id}','userApprove');
    Route::post('update-me','updateMe');

});

Route::controller(CertificatesController::class)->group(function () {
   
    Route::post('/certificates', 'index');
    Route::get('/certificate/{id}','show');
    Route::post('store-certificate', 'store');
    Route::post('/certificate/{id}','update');
    Route::post('/certificates/{id}','destroy');
  

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
