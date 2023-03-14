<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware'=>'api',
              'prefix'=>'auth'
],function($router){

Route:: post("branch-signup","App\Http\Controllers\AdminController@branchSignUp");
Route:: post("admin-signup","App\Http\Controllers\AdminController@adminSignUp");
Route:: post("admin-login","App\Http\Controllers\AdminController@adminLogin");
Route:: post("cust-allfeed","App\Http\Controllers\AdminController@getAllComplains");
Route:: post("cust-feednow","App\Http\Controllers\AdminController@getComplainToday");

});


Route::group(['middleware'=>'api',
              'prefix'=>'branch'
],function($router){

Route:: post("branch-login","App\Http\Controllers\UserController@userLogin");
Route:: post("cust-feedback","App\Http\Controllers\UserController@custFeedback");

});
