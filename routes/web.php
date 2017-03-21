<?php

// Authentication routes...
Route::get('auth/login', 'AuthController@getLogin');
Route::post('auth/login', 'AuthController@postLogin');
Route::get('auth/logout', 'AuthController@getLogout');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');


Route::group(['middleware' => 'auth'], function () {
    Route::resource('admin/client', 'ClientController');
    Route::get('admin/client/reset-db/{id}', 'ClientController@resetDb')->name('admin.client.resetDb');
    Route::post('admin/client/import-db/', 'ClientController@importDb')->name('admin.client.importDb');
    Route::get('admin/client/migrate-db/{id}', 'ClientController@migrateDb')->name('admin.client.migrateDb');
    Route::get('admin/client/config/{id}', 'ClientController@config')->name('admin.client.config');
    Route::post('admin/client/config/{id}', 'ClientController@postConfig');
    Route::post('admin/client/import-table', 'ClientController@importTable');

    Route::resource('/auth/user-groups/', 'Auth\UserGroupController');
    Route::post('/auth/user-groups/add-user-to-group', 'Auth\UserGroupController@addUserToGroup');
    Route::post('/auth/user-groups/remove-user-from-group', 'Auth\UserGroupController@removeUserFromGroup');
    Route::get('/admin', 'AdminController@index');

    Route::get('/', function (){
        return view('app.index');
    })->name('dashboard');

    Route::resource('properties', 'PropertyController');

});

// need to replace
Route::get('settings', '\CityNexus\CityNexus\Http\CitynexusSettingsController@getIndex');
Route::get('settings/create-widget', '\CityNexus\CityNexus\Http\WidgetController@getCreate');
Route::post('settings/create-widget', '\CityNexus\CityNexus\Http\WidgetController@postCreate');
Route::post('settings/update-dashboard', '\CityNexus\CityNexus\Http\CitynexusSettingsController@postUpdateDashboard');

Route::resource('admin/client', 'ClientController');
Route::get('admin/client/reset-db/{id}', 'ClientController@resetDb')->name('admin.client.resetDb');
Route::post('admin/client/import-db/', 'ClientController@importDb')->name('admin.client.importDb');
Route::get('admin/client/migrate-db/{id}', 'ClientController@migrateDb')->name('admin.client.migrateDb');
Route::get('admin/client/config/{id}', 'ClientController@config')->name('admin.client.config');
Route::post('admin/client/config/{id}', 'ClientController@postConfig');
Route::post('admin/client/import-table', 'ClientController@importTable');


Route::get('test', function(){
    return view('master.auth');
});