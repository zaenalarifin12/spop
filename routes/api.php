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

Route::group(["middleware" => ["jwt.verify"]], function(){
    Route::get('pemutakhiran',                                          "Api\PemutakhiranController@index");
    Route::get('pemutakhiran/cari',                                     'Api\PemutakhiranController@cari');
    Route::get('pemutakhiran/create/{uuid}',                            'Api\PemutakhiranController@create');
    Route::post('pemutakhiran/create/{uuid}',                           'Api\PemutakhiranController@store');
    Route::get('/pemutakhiran/{uuid}',                                  'Api\PemutakhiranController@show');
    Route::get('/pemutakhiran/{uuid}/edit',                             'Api\PemutakhiranController@edit');
    Route::put('/pemutakhiran/{uuid}',                                  'Api\PemutakhiranController@update');
    Route::get('/pemutakhiran/{uuid}/bangunan/create',                  'Api\PemutakhiranController@createBangunan');
    Route::post('/pemutakhiran/{uuid}/bangunan/create',                 'Api\PemutakhiranController@storeBangunan');
    Route::get('/pemutakhiran/{uuid}/bangunan/{uuid_bangunan}',         'Api\PemutakhiranController@showBangunan');
    Route::get('/pemutakhiran/{uuid}/bangunan/{uuid_bangunan}/edit',    'Api\PemutakhiranController@editBangunan');
    Route::put('/pemutakhiran/{uuid}/bangunan/{uuid_bangunan}',         'Api\PemutakhiranController@updateBangunan');
    Route::delete('/pemutakhiran/{uuid}/bangunan/{uuid_bangunan}',      'Api\PemutakhiranController@destroyBangunan');
    Route::delete('/pemutakhiran/{uuid}/gambar/{id}',                   'Api\GambarController@destroy');
});    


Route::get("/v1/getKabupaten",    "Api\GetApiLokasiController@getKabupaten");
Route::get("/v1/getKecamatan",    "Api\GetApiLokasiController@getKecamatan");
Route::get("/v1/getDesa",         "Api\GetApiLokasiController@getDesa");