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


Route::get('email_notif','PermitController@email_notif')->name('email-notif');

Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    
    Route::group(['middleware' => 'deactivate'], function() {
        Route::get('/', 'HomeController@index')->name('home')->middleware('role');
        Route::get('/home', 'HomeController@index')->name('home')->middleware('role');
        Route::get('/search', 'HomeController@search')->name('search');
    
        Route::get('/request', 'RequestController@index')->name('requests');
        Route::get('/change-requests','RequestController@changeRequests')->name('change-requests');
        Route::post('change-request-edit/{id}','RequestController@editRequest')->name('change-requests');
        Route::get('/for-approval','RequestController@forApproval')->name('for-approval');
        Route::post('/edit-title/{id}','RequestController@editTile');
    
        Route::get('/documents', 'DocumentController@index')->name('documents');
        Route::post('/upload-file/{id}', 'DocumentController@upload')->name('documents');
        Route::post('view-document/edit-document/{id}','DocumentController@edit');
        Route::get('audits','DocumentController@audit')->name('audit');
        Route::post('upload-document','DocumentController@store')->name('documents');
        Route::get('/view-document/{id}','DocumentController@show')->name('documents');
        Route::get('/view-pdf/{id}','DocumentController@showPDF')->name('documents');
    
    
        //copyrequest
        Route::post('copy-request','CopyController@store');
        Route::post('copy-request-action/{id}','CopyController@action');
    
        //ChangeRequest
        Route::post('change-request','RequestController@store');
        Route::post('change-request-action/{id}','RequestController@action');
        Route::post('new-change-request','RequestController@new_request');
    
    
        Route::get('/permits', 'PermitController@index')->name('permits');
        Route::post('new-permit', 'PermitController@store')->name('permits');
        Route::post('/upload-permit/{id}', 'PermitController@upload')->name('permits');
        Route::post('/change-department/{id}', 'PermitController@update')->name('permits');
        Route::post('change-type/{id}','PermitController@change_type')->name('permits');
        Route::post('inactive-permits/{id}', 'PermitController@inactivePermits');
        Route::post('activate-permits/{id}', 'PermitController@activatePermits');
    
    
        Route::get('/companies', 'CompanyController@index')->name('settings');
        Route::post('/new-company', 'CompanyController@store')->name('settings');
        Route::post('deactivate-company', 'CompanyController@deactivate')->name('settings');
        Route::post('activate-company', 'CompanyController@activate')->name('settings');
    
        Route::get('/departments', 'DepartmentController@index')->name('settings');
        Route::post('/new-department', 'DepartmentController@store')->name('settings');
        Route::post('deactivate-department', 'DepartmentController@deactivate')->name('settings');
        Route::post('activate-department', 'DepartmentController@activate')->name('settings');
        Route::post('edit-department/{id}','DepartmentController@update')->name('settings');
    
        Route::get('remove-approvers','RequestController@removeApprover')->name('remove-approvers');
        Route::post('update-approvers/{id}','RequestController@removeApp')->name('remove-approvers');
    
        //Users
        Route::get('/users', 'UserController@index')->name('settings');
        Route::post('new-account', 'UserController@create')->name('settings');
        Route::post('/change-password/{id}', 'UserController@changepassword')->name('settings');
        Route::post('/edit-user/{id}', 'UserController@edit_user')->name('settings');
        Route::post('deactivate-user', 'UserController@deactivate_user')->name('settings');
        Route::post('activate-user', 'UserController@activate_user')->name('settings');
    
    
        //DCO
        Route::get('dco','DcoController@index')->name('settings');
        Route::post('edit-dco/{id}','DcoController@update')->name('settings');
    
    
        Route::get('/logs', 'AuditController@index')->name('reports');
        Route::get('copy-reports','CopyController@copyReports')->name('reports');
        Route::get('dicr-reports','RequestController@changeReports')->name('reports');
        Route::get('dco-reports','RequestController@docReports')->name('reports');
    
        Route::get('test-mail','RequestController@test');
    
    
        Route::get('acknowledgement','AcknowledgementController@index')->name('acknowledgement');
        Route::get('uploaded-acknowledgement','AcknowledgementController@uploaded')->name('acknowledgement');
        Route::post('upload-acknowledgement/{id}','AcknowledgementController@store')->name('acknowledgement');
    
        Route::post('change-public','DocumentController@changePublic');
    
        // Pre Assessment
        Route::get('pre_assessment', 'PreAssessmentController@index')->name('pre_assessment');
        Route::post('approve_pre_assessment/{id}', 'PreAssessmentController@approve');
        Route::post('edit_upload', 'AcknowledgementController@editUpload');
    
        // Delayed
        Route::get('/delayed_request', 'RequestController@delayedRequest');
        Route::get('/delayed_pre_assessment', 'PreAssessmentController@delayedRequest');
    
        // Archive Permits
        Route::get('/archive_permits', 'PermitController@viewArchived');

        // Memorandum
        Route::get('memorandum', 'MemorandumController@index');
        Route::post('store_memorandum', 'MemorandumController@store');
        Route::post('update_memorandum/{id}', 'MemorandumController@update');
        Route::post('update_status/{id}', 'MemorandumController@updateStatus');
        Route::post('delete_memo', 'MemorandumController@destroy');
    });

});


Route::get('/document/{documentId}', function($documentId) {
    return view('public.document');
})->name('document.public.view');
