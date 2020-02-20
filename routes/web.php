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


Route::get('/', "HomeController@index");

Route::group(["middleware" => ["auth"]], function(){

    Route::group(["middleware" => ["admin"]], function() {
        Route::get('/rujukan',                               'RujukanController@index');
        Route::get('/rujukan/json',                          'RujukanController@json');

        Route::get('/spop',                                  'SpopController@index');
        Route::get('/spop/json',                             'SpopController@json');
        
        Route::get('/users',                                'UserController@index');
        Route::get('/users/json',                           'UserController@json');

        Route::get('/perekaman',                            'PerekamanController@index');
        Route::get('/perekaman/json',                       'PerekamanController@json');
        Route::get('/perekaman/create',                     'PerekamanController@create');
    });
    Route::get('/pemutakhiran/cari',                     'PemutakhiranController@cari');
    Route::get('/pemutakhiran/create/{nop}',             'PemutakhiranController@create');
    Route::post('/pemutakhiran/create/{nop}',            'PemutakhiranController@store');
    Route::get('/pemutakhiran/{nop}',                    'PemutakhiranController@show');
    Route::get('/pemutakhiran/{nop}/edit',               'PemutakhiranController@edit');
    Route::put('/pemutakhiran/{nop}',                    'PemutakhiranController@update');
    Route::get('/pemutakhiran/{nop}/bangunan/create',   'PemutakhiranController@createBangunan');
    Route::post('/pemutakhiran/{nop}/bangunan/create',   'PemutakhiranController@storeBangunan');
    
    Route::get('/pemutakhiran/{nop}/bangunan/{id}',      'PemutakhiranController@showBangunan');
    Route::get('/pemutakhiran/{nop}/bangunan/{id}/edit', 'PemutakhiranController@editBangunan');
    Route::put('/pemutakhiran/{nop}/bangunan/{id}',      'PemutakhiranController@updateBangunan');
    Route::delete('/pemutakhiran/{nop}/bangunan/{id}',      'PemutakhiranController@destroyBangunan');

    Route::get('/profile/{nip}',            'ProfileController@show');

    Route::get('/profile/{nip}/edit',       'ProfileController@edit');

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/register', function() {
    return view("auth.register");
});
Route::get('/profile', function() {
    return view("profile.show");
});
Route::get('/pemutakhiran', function() {
    return view("pemutakhiran.index");
});