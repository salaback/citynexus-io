<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/home', function () {
    return redirect('/');
});

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', '\CityNexus\CityNexus\Http\AdminController@postCustomLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

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


Route::get('test', function(){
   return 'Hello';
});
