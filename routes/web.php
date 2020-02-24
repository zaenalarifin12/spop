<?php

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


Route::get('/', "GenerateUuidRujukanController@index");
// Route::get('/', "HomeController@index");

Route::group(["middleware" => ["auth"]], function(){

    Route::group(["middleware" => ["admin"]], function() {

        Route::get('/rujukan',                               'RujukanController@index');
        Route::get('/rujukan/json',                          'RujukanController@json');

        Route::get('/users',                                'UserController@index');
        Route::get('/users/json',                           'UserController@json');
    });
    Route::get('/pemutakhiran',                                      "PemutakhiranController@index");
    Route::get('/pemutakhiran/json',                                 "PemutakhiranController@json");
    Route::get('/pemutakhiran/cari',                                 'PemutakhiranController@cari');
    Route::get('/pemutakhiran/create/{uuid}',                        'PemutakhiranController@create');
    Route::post('/pemutakhiran/create/{uuid}',                       'PemutakhiranController@store');
    Route::get('/pemutakhiran/{uuid}',                               'PemutakhiranController@show');
    Route::get('/pemutakhiran/{uuid}/edit',                          'PemutakhiranController@edit');
    Route::put('/pemutakhiran/{uuid}',                               'PemutakhiranController@update');
    Route::get('/pemutakhiran/{uuid}/bangunan/create',               'PemutakhiranController@createBangunan');
    Route::post('/pemutakhiran/{uuid}/bangunan/create',              'PemutakhiranController@storeBangunan');
    Route::get('/pemutakhiran/{uuid}/bangunan/{uuid_bangunan}',      'PemutakhiranController@showBangunan');
    Route::get('/pemutakhiran/{uuid}/bangunan/{uuid_bangunan}/edit', 'PemutakhiranController@editBangunan');
    Route::put('/pemutakhiran/{uuid}/bangunan/{uuid_bangunan}',      'PemutakhiranController@updateBangunan');
    Route::delete('/pemutakhiran/{uuid}/bangunan/{uuid_bangunan}',   'PemutakhiranController@destroyBangunan');

    Route::get('/perekaman',                                      'PerekamanController@index');
    Route::get('/perekaman/json',                                 'PerekamanController@json');
    Route::get('/perekaman/create',                               'PerekamanController@create');
    Route::post('/perekaman/create',                              'PerekamanController@store');
    Route::get('/perekaman/{uuid}',                               'PerekamanController@show');
    Route::get('/perekaman/{uuid}/edit',                          'PerekamanController@edit');
    Route::put('/perekaman/{uuid}',                               'PerekamanController@update');
    Route::get('/perekaman/{uuid}/bangunan/create',               'PerekamanController@createBangunan');
    Route::post('/perekaman/{uuid}/bangunan/create',              'PerekamanController@storeBangunan');
    Route::get('/perekaman/{uuid}/bangunan/{uuid_bangunan}',      'PerekamanController@showBangunan');
    Route::get('/perekaman/{uuid}/bangunan/{uuid_bangunan}/edit', 'PerekamanController@editBangunan');
    Route::put('/perekaman/{uuid}/bangunan/{uuid_bangunan}',      'PerekamanController@updateBangunan');
    Route::delete('/perekaman/{uuid}/bangunan/{uuid_bangunan}',   'PerekamanController@destroyBangunan');


    Route::get('/profile/{nip}',            'ProfileController@show');
    Route::get('/profile/{nip}/edit',       'ProfileController@edit');
    Route::put('/profile/{nip}',            'ProfileController@update');

    Route::get('/profile', function() {
        return view("profile.show");
    });

    Route::get('/home', 'HomeController@index')->name('home');
});

Auth::routes();

Route::get('/register', function() {
    return view("auth.register");
});
