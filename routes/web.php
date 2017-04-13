<?php

// Authentication routes...
Route::get('auth/login', 'AuthController@getLogin')->name('login');
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

    Route::get('/', 'DashboardController@index')->name('dashboard');

    Route::resource('properties', 'PropertyController');
    Route::get('properties/geocode/{id}', 'PropertyController@geocode')->name('property.geocode');

    // dataset routes
    Route::get('/dataset/any-data', 'DatasetController@anyData')->name('dataset.anydata');
    Route::resource('/dataset', 'DatasetController');


    // Comments
    Route::resource('/comments', 'CommentController');

    // Files
    Route::resource('/files', 'FileController');

    // Entities
    Route::resource('/entities', 'EntityController');

    // Tags
    Route::resource('/tags', 'TagController');

    // Search
    Route::get('/search/suggestions/{query?}', 'SearchController@suggestions')->name('search.suggestions');
    Route::get('/search/', 'SearchController@search')->name('search.search');

    // Uploaders routes
    Route::resource('dataset/upload', 'UploadController');
    Route::resource('uploader', 'UploaderController');
    Route::post('/uploader/schema', 'UploaderController@schema')->name('uploader.schema');
    Route::get('/uploader/address-sync/{id}', 'UploaderController@addressSync')->name('uploader.addressSync');
    Route::get('/uploader/entity-sync/{id}', 'UploaderController@entitySync')->name('uploader.entitySync');
    Route::get('/uploader/filters/{id}', 'UploaderController@filters')->name('uploader.filters');

    Route::post('/uploader/post', 'UploaderController@post')->name('uploader.post');

    Route::get('view/map', 'ViewController@map')->name('map');
    Route::post('view/map', 'ViewController@mapData');

    Route::get('/get-notification/{id}', function($id){

        $notification = \Illuminate\Support\Facades\Auth::user()->notifications()->where('id', $id)->first();

        if($notification)
        {
            $notification->markAsRead();
            return redirect($notification->data['clickBack']);
        }
        else{
            return back()->withErrors('We could not found the specified notification');
        }

    })->name('getNotification');
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
    return view('welcome');
});