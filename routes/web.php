<?php

// Authentication routes...
Route::get('auth/login', 'AuthController@getLogin')->name('login');
Route::post('auth/login', 'AuthController@postLogin');
Route::get('auth/logout', 'AuthController@getLogout')->name('logout');
Route::get('activate-account', 'AuthController@activate');
Route::post('activate-account', 'AuthController@postActivate');


Route::group(['middleware' => 'auth', 'prefix' => 'organization'], function(){
    Route::get('/', 'Admin\OrganizationSettingsController@index');
    Route::get('/edit-user/{id}', 'Admin\OrganizationSettingsController@editUser')->name('admin.user.edit');
    Route::post('/edit-user/{id}', 'Admin\OrganizationSettingsController@storeUser');
    Route::resource('/users', 'Admin\UserController');
});


//// Password reset link request routes...
//Route::get('password/email', 'Auth\PasswordController@getEmail');
//Route::post('password/email', 'Auth\PasswordController@postEmail');
//
//// Password reset routes...
//Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
//Route::post('password/reset', 'Auth\PasswordController@postReset');


Route::group(['middleware' => ['auth']], function () {
    Route::resource('admin/client', 'Admin\ClientController');
    Route::get('admin/client/reset-db/{id}', 'Admin\ClientController@resetDb')->name('admin.client.resetDb');
    Route::post('admin/client/import-db/', 'Admin\ClientController@importDb')->name('admin.client.importDb');
    Route::get('admin/client/migrate-db/{id}', 'Admin\ClientController@migrateDb')->name('admin.client.migrateDb');
    Route::get('admin/client/config/{id}', 'Admin\ClientController@config')->name('admin.client.config');
    Route::post('admin/client/config/{id}', 'Admin\ClientController@postConfig');
    Route::post('admin/client/import-table', 'Admin\ClientController@importTable');
    Route::get('admin/client/upgrade/{id}', 'Admin\ClientController@upgrade')->name('admin.client.upgrade');

    Route::resource('/auth/groups', 'Auth\UserGroupController');
    Route::post('/auth/groups/add-user-to-group', 'Auth\UserGroupController@addUserToGroup')->name('group.addUser');
    Route::post('/auth/groups/remove-user-from-group', 'Auth\UserGroupController@removeUserFromGroup')->name('group.removeUser');
    Route::get('/admin', 'AdminController@index');

    Route::get('/', 'DashboardController@index')->name('dashboard');

    Route::resource('properties', 'PropertyController');
    Route::get('properties/geocode/{id}', 'PropertyController@geocode')->name('property.geocode');

    // dataset routes
    Route::get('/dataset/any-data', 'Frontend\DatasetController@anyData')->name('dataset.anydata');
    Route::resource('/dataset', 'Frontend\DatasetController');


    // Comments
    Route::resource('/comments', 'CommentController');

    // Files
    Route::resource('/files', 'FileController');

    // Entities
    Route::resource('/entities', 'EntityController');

    // Tags
    Route::resource('/tag', 'TagController');

    // Search
    Route::get('/search/suggestions/{query?}', 'SearchController@suggestions')->name('search.suggestions');
    Route::get('/search/', 'SearchController@search')->name('search.search');

    // Uploaders routes
    Route::resource('datasets/upload', 'Frontend\UploadController');
    Route::resource('uploader', 'Frontend\UploaderController');
    Route::get('/uploader/create-schema/{id}', 'Frontend\UploaderController@createMap')->name('uploader.createMap');

    Route::post('/uploader/create-schema/', 'Frontend\UploaderController@storeMap')->name('uploader.storeMap');
    Route::post('/uploader/schema', 'Frontend\UploaderController@schema')->name('uploader.schema');

    Route::get('/uploader/address-sync/{id}', 'Frontend\UploaderController@addressSync')->name('uploader.addressSync');
    Route::get('/uploader/entity-sync/{id}', 'Frontend\UploaderController@entitySync')->name('uploader.entitySync');
    Route::get('/uploader/filters/{id}', 'Frontend\UploaderController@filters')->name('uploader.filters');

    Route::post('/uploader/store-sync', 'Frontend\UploaderController@storeSync')->name('uploader.storeSync');
    Route::post('/uploader/remove-sync/{id}', 'Frontend\UploaderController@removeSync')->name('uploader.removeSync');

    Route::post('/uploader/post', 'Frontend\UploaderController@post')->name('uploader.post');

    Route::get('/upload/process/{id?}', 'Frontend\UploadController@process')->name('upload.process');

    Route::get('view/map', 'Frontend\ViewController@map')->name('map');
    Route::post('view/map', 'Frontend\ViewController@mapData');

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



//// need to replace
//Route::get('settings', '\CityNexus\CityNexus\Http\CitynexusSettingsController@getIndex');
//Route::get('settings/create-widget', '\CityNexus\CityNexus\Http\WidgetController@getCreate');
//Route::post('settings/create-widget', '\CityNexus\CityNexus\Http\WidgetController@postCreate');
//Route::post('settings/update-dashboard', '\CityNexus\CityNexus\Http\CitynexusSettingsController@postUpdateDashboard');

Route::get('test', function(){
    return view('welcome');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
