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


Route::post("login",    "Api\AuthController@login");

Route::group(["middleware" => ["jwt.auth"]], function(){
    Route::get('pemutakhiran', "Api\PemutakhiranController@index");

    Route::get('perekaman',             "Api\PerekamanController@index");
    Route::get('/perekaman/create',     'Api\PerekamanController@create');
    Route::post('/perekaman/create',    'Api\PerekamanController@store');
});
    


Route::get("/v1/getKabupaten",    "Api\GetApiLokasiController@getKabupaten");
Route::get("/v1/getKecamatan",    "Api\GetApiLokasiController@getKecamatan");
Route::get("/v1/getDesa",         "Api\GetApiLokasiController@getDesa");