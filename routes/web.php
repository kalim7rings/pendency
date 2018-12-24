<?php


Route::get('dashboard','DashboardController@index')->name('dashboard');
Route::post('view-document','DashboardController@viewDocument')->name('viewDocument');
//Route::post('/upload-document','UploadDocumentController@upload')->name('upload');

Route::get('/logout', 'DashboardController@logout')->name('logout');

Route::get('/{token}','HomeController@index');




/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('token/login/{token}', 'TokenController@login')->name('token.login');



/* ----------------------------- API ------------------------ */

Route::group(['prefix' => '/v1', 'namespace' => 'API', 'as' => 'api.'], function () {
    Route::post('validate-otp', 'ValidateOtpController@store')->name('otp.validate');
    Route::post('otp','SendOtpController@store')->name('otp.store');

    Route::post('doc-list','DocumentController@docList')->name('doc.list');
    Route::post('document-upload','DocumentController@upload')->name('doc.upload');

    Route::post('password-check','DashboardController@update')->name('password.update');

});

