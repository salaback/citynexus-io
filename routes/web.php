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
    Route::get('response/dropbox', 'Backend\ApiAuthController@dropbox')->name('apiAuth.dropbox');
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

    Route::get('/properties/get-units/{id?}', 'Frontend\PropertyController@getUnits')->name('property.getUnits');
    Route::resource('properties', 'Frontend\PropertyController');
    Route::get('properties\all-data', 'Frontend\PropertyController@allData')->name('property.allData');
    Route::get('properties/merge-search/{id}/{string?}', 'Frontend\PropertyController@mergeSearch')->name('properties.mergeSearch');
    Route::post('properties/merge', 'Frontend\PropertyController@mergeProperties')->name('properties.merge');
    Route::get('properties/geocode/{id}', 'Frontend\PropertyController@geocode')->name('property.geocode');

    // dataset routes
    Route::get('/dataset/raw-data/{id}', 'Frontend\DatasetController@rawData')->name('dataset.rawData');
    Route::get('/dataset/any-data', 'Frontend\DatasetController@anyData')->name('dataset.anydata');
    Route::resource('/dataset', 'Frontend\DatasetController');
    Route::get('/dataset/datapoint-info/{id?}/{key?}', 'Frontend\DatasetController@datapointInfo')->name('dataset.datapointInfo');


    // Comments
    Route::resource('/comments', 'Frontend\CommentController');

    // Files
    Route::get('/files/download/{id?}', 'Backend\FileController@download')->name('files.download');
    Route::resource('/files', 'Backend\FileController');

    // Entities
    Route::get('entity/set-primary-address/{id}/{address}', 'Frontend\EntityController@setPrimaryAddress')->name('entity.setPrimaryAddress');
    Route::get('entity/remove-relationship/{id}', 'Frontend\EntityController@removeRelationship')->name('entity.removeRelationship');
    Route::post('entity/add-relationship', 'Frontend\EntityController@addRelationship')->name('entity.addRelationship');

    Route::resource('/entity', 'Frontend\EntityController');
    Route::get('entity\all-data', 'Frontend\EntityController@allData')->name('entity.allData');

    // Meetings
    Route::resource('/meetings/agenda', 'Frontend\AgendaController');

    // Analytics
    Route::get('/analytics/score/refresh/{id}', 'Frontend\ScoreController@refresh')->name('score.refresh');
    Route::resource('/analytics/score', 'Frontend\ScoreController');
    Route::post('/analytics/score/create-element', 'Frontend\ScoreController@createElement')->name('analytics.score.create.element');

    // Tags
    Route::resource('backend/tag', 'Backend\TagController');
    Route::post('backend/tag/attach', 'Backend\TagController@attach')->name('backend.tag.attach');
    Route::post('backend/tag/detach', 'Backend\TagController@detach')->name('backend.tag.detach');
    // Search
    Route::get('/search/suggestions/{query?}', 'Frontend\SearchController@suggestions')->name('search.suggestions');
    Route::get('/search/', 'Frontend\SearchController@search')->name('search.search');

    // Uploaders routes
    Route::post('uploader/sql-test', 'Frontend\UploaderController@testSql')->name('uploader.sqlTest');
    Route::resource('datasets/upload', 'Frontend\UploadController');
    Route::resource('uploader', 'Frontend\UploaderController');
    Route::get('/uploader/create-schema/{id}', 'Frontend\UploaderController@createMap')->name('uploader.createMap');

    // Tasks
    Route::resources([
        'tasks/task' => 'Backend\TaskController',
        'tasks/list'=> 'Backend\TaskListController'
    ]);


    Route::post('/uploader/create-schema/', 'Frontend\UploaderController@storeMap')->name('uploader.storeMap');
    Route::post('/uploader/schema', 'Frontend\UploaderController@schema')->name('uploader.schema');


    Route::get('/uploader/address-sync/{id}', 'Frontend\UploaderController@addressSync')->name('uploader.addressSync');
    Route::get('/uploader/entity-sync/{id}', 'Frontend\UploaderController@entitySync')->name('uploader.entitySync');
    Route::get('/uploader/tag-sync/{id}', 'Frontend\UploaderController@tagSync')->name('uploader.tagSync');
    Route::get('/uploader/timestamp-sync/{id}', 'Frontend\UploaderController@timestampSync')->name('uploader.timestampSync');
    Route::get('/uploader/primary-id-sync/{id}', 'Frontend\UploaderController@primaryIdSync')->name('uploader.primaryIdSync');
    Route::get('/uploader/filters/{id}', 'Frontend\UploaderController@filters')->name('uploader.filters');

    Route::post('/uploader/store-sync', 'Frontend\UploaderController@storeSync')->name('uploader.storeSync');
    Route::post('/uploader/remove-sync/{id}', 'Frontend\UploaderController@removeSync')->name('uploader.removeSync');
    Route::post('/uploader/post', 'Frontend\UploaderController@post')->name('uploader.post');

    Route::post('documents/templates/get-form/{id?}', 'Frontend\DocumentTemplateController@getForm')->name('templates.getForm');
    Route::resource('documents/templates', 'Frontend\DocumentTemplateController');

    Route::post('documents/queue/print-queue/{id?}', 'Frontend\PrintQueueController@printQueue')->name('queue.print');
    Route::post('documents/queue/clear-from-queue', 'Frontend\PrintQueueController@clearFromQueue')->name('queue.clear');

    Route::resource('documents/queue', 'Frontend\PrintQueueController');
    Route::resource('documents', 'Frontend\DocumentController');

    Route::resource('form', 'Frontend\FormController');

    Route::get('/upload/process/{id?}', 'Frontend\UploadController@process')->name('upload.process');

    Route::get('view/map', 'Frontend\ViewController@map')->name('map');
    Route::post('view/map', 'Frontend\ViewController@mapData')->name('mapData');

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
