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
Route::get('auth/google', 'GoogleController@redirectToGoogle');
Route::get('auth/google/callback','GoogleController@handleGoogleCallback');

Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    Route::group(['middleware' => 'deactivate'], function() {
        
        // Documents
        Route::get('/documents', 'DocumentController@index')->name('documents');
        Route::prefix('documents')->group(function() {
            Route::get('create/{id?}', 'DocumentController@create')->name('documents.create');
            Route::get('signature/{id}', 'DocumentController@signature')->name('documents.signature');
            Route::get('folder/{id}','DocumentController@folderView');
            Route::get('view-document/{id}','DocumentController@show');
            Route::get('/view-pdf/{id}','DocumentController@showPDF')->name('documents');

            Route::post('signaturePosition','DocumentController@signaturePosition');
            Route::post('store', 'DocumentController@store')->name('documents.store');
            Route::post('store-folder','DocumentController@addFolder');
            Route::post('rename-folder/{id}','DocumentController@renameFolder');
            Route::post('delete-folder/{id}','DocumentController@deleteFolder');
            Route::post('edit_date_approved/{id}', 'DocumentController@editDateApproved');
            Route::post('upload-document-folder','DocumentController@uploadDocumentFolder');
            
        });

         //Users
        Route::get('/users', 'UserController@index')->name('settings');
        Route::prefix('users')->group(function() {
            Route::post('new-account', 'UserController@create')->name('settings');
            Route::post('/edit-user', 'UserController@edit_user')->name('settings');
            Route::post('/change-password', 'UserController@changepassword')->name('settings');
            Route::post('deactivate-user', 'UserController@deactivate_user')->name('settings');
            Route::post('activate-user', 'UserController@activate_user')->name('settings');
        });

        //ChangeRequest
        Route::get('/change-requests','RequestController@changeRequests')->name('change-requests');
        Route::prefix('change-request')->group(function() {
            Route::get('for_approval/{id}', 'RequestController@show');
            Route::get('view-change-request/{id}','RequestController@viewChangeRequest');
            
            Route::post('store','RequestController@store');
            Route::post('comments', 'RequestController@comments');
            Route::post('change-request-action/{id}','RequestController@action');
            Route::post('confirm-password','RequestController@confirmPassword');
        });

        // Copy Request
        Route::prefix('copy_request')->group(function() {
            Route::post('/store','CopyController@store');
            Route::post('copy-request-action/{id}','CopyController@action');
        });

        // Approver stamp
        Route::get('/approver-stamp','ApproverStampController@index');
        Route::prefix('approver-stamp')->group(function() {
            Route::post('/store', 'ApproverStampController@store');
        });

        // Roles & Permissions
        Route::get('roles','RoleController@index');
        Route::prefix('roles')->group(function() {
            Route::post('/store','RoleController@store');
            Route::post('/update/{id}','RoleController@update');
            Route::post('/add-permission/{id}','RoleController@addPermission');
        });
        Route::prefix('permission')->group(function() {
            Route::post('/store','PermissionController@store');
            Route::post('/update/{id}','PermissionController@update');
        });

        // Permits
        Route::get('/permits', 'PermitController@index')->name('permits');
        Route::prefix('permits')->group(function() {
            Route::post('/store', 'PermitController@store')->name('permits');
            Route::post('/upload/{id}', 'PermitController@upload')->name('permits');
            Route::post('change-type/{id}','PermitController@change_type')->name('permits');
        });
        // Route::post('/change-department/{id}', 'PermitController@update')->name('permits');
        // Route::post('inactive-permits/{id}', 'PermitController@inactivePermits');
        // Route::post('activate-permits/{id}', 'PermitController@activatePermits');

        // Home
        Route::get('/', 'HomeController@index')->name('home')->middleware('role');
        Route::get('/home', 'HomeController@index')->name('home')->middleware('role');
        Route::get('/search', 'HomeController@search')->name('search');
    
        Route::get('/request', 'RequestController@index')->name('requests');
        Route::post('change-request-edit/{id}','RequestController@editRequest')->name('change-requests');
        Route::get('/for-approval','RequestController@forApproval')->name('for-approval');
        Route::post('/edit-title/{id}','RequestController@editTile');
    
        Route::post('/upload-file/{id}', 'DocumentController@upload')->name('documents');
        Route::post('view-document/edit-document/{id}','DocumentController@edit');
        Route::get('audits','DocumentController@audit')->name('audit');
        Route::post('upload-document','DocumentController@store')->name('documents');
    
        // Route::get('/companies', 'CompanyController@index')->name('settings');
        // Route::post('/new-company', 'CompanyController@store')->name('settings');
        // Route::post('deactivate-company', 'CompanyController@deactivate')->name('settings');
        // Route::post('activate-company', 'CompanyController@activate')->name('settings');
    
        Route::get('/departments', 'DepartmentController@index')->name('settings');
        Route::post('/new-department', 'DepartmentController@store')->name('settings');
        Route::post('deactivate-department', 'DepartmentController@deactivate')->name('settings');
        Route::post('activate-department', 'DepartmentController@activate')->name('settings');
        Route::post('edit-department/{id}','DepartmentController@update')->name('settings');

        Route::get('/teams', 'TeamsController@index')->name('teams.index');
        Route::post('/teams', 'TeamsController@store')->name('teams.store');
        Route::put('/teams/{id}', 'TeamsController@update')->name('teams.update');
        Route::delete('/teams/{id}', 'TeamsController@destroy')->name('teams.destroy');
    
        // Route::get('remove-approvers','RequestController@removeApprover')->name('remove-approvers');
        // Route::post('update-approvers/{id}','RequestController@removeApp')->name('remove-approvers');
    
        Route::get('/users/data', 'UserController@getUsersData')->name('users.data');
        Route::post('/users/modals', 'UserController@getUserModals')->name('users.modals');
    
    
        //DCO
        // Route::get('dco','DcoController@index')->name('settings');
        // Route::post('edit-dco/{id}','DcoController@update')->name('settings');
    
    
        // Route::get('/logs', 'AuditController@index')->name('reports');
        // Route::get('copy-reports','CopyController@copyReports')->name('reports');
        // Route::get('dicr-reports','RequestController@changeReports')->name('reports');
        // Route::get('dco-reports','RequestController@docReports')->name('reports');
    
        // Route::get('test-mail','RequestController@test');
    
    
        // Route::get('acknowledgement','AcknowledgementController@index')->name('acknowledgement');
        // Route::get('uploaded-acknowledgement','AcknowledgementController@uploaded')->name('acknowledgement');
        // Route::post('upload-acknowledgement/{id}','AcknowledgementController@store')->name('acknowledgement');
    
        // Route::post('change-public','DocumentController@changePublic');
    
        // // Pre Assessment
        // Route::get('pre_assessment', 'PreAssessmentController@index')->name('pre_assessment');
        // Route::post('approve_pre_assessment/{id}', 'PreAssessmentController@approve');
        // Route::post('edit_upload', 'AcknowledgementController@editUpload');
    
        // // Delayed
        // Route::get('/delayed_request', 'RequestController@delayedRequest');
        // Route::get('/delayed_pre_assessment', 'PreAssessmentController@delayedRequest');
    
        // // Archive Permits
        // Route::get('/archive_permits', 'PermitController@viewArchived');

        // // Memorandum
        // Route::get('memorandum', 'MemorandumController@index');
        // Route::post('store_memorandum', 'MemorandumController@store');
        // Route::post('update_memorandum/{id}', 'MemorandumController@update');
        // Route::post('update_status/{id}', 'MemorandumController@updateStatus');
        // Route::post('delete_memo', 'MemorandumController@destroy');
    });

});

Route::get('document/{id}', 'DocumentController@publicDocument');
Route::get('/change-request/{id}', 'DocumentController@viewChangeRequest')->name('change-request.view');
// Route::get('/document/{documentId}', function($documentId) {
//     return view('public.document');
// })->name('document.public.view');

Route::get('/pdf-viewer/{file}', function($file) {
    return view('pdf-viewer', ['pdfFile' => $file]);
})->name('pdf.viewer');

Route::get('mailable', function () {
    $change_request = App\ChangeRequest::find(1);
    $user = App\User::find(1);
 
    // return new App\Mail\RequestDocumentApproval($change_request,$user);
    return new App\Mail\ApprovedRequestEmail($change_request,$user);
});