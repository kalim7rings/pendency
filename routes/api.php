<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


/*

// its not in use since its declare in web.php because session is not accessible in api.php file

Route::group(['prefix' => '/v1', 'namespace' => 'API', 'as' => 'api.'], function () {
    Route::post('validate-otp', 'ValidateOtpController@store')->name('otp.validate');
    Route::post('otp','SendOtpController@store')->name('otp.store');
});
*/
