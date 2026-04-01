<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\ForgotPasswordOtpController;
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

// use App\DateApprovedLog;



// 404 error


Auth::routes();
Route::get('email_notif','PermitController@email_notif')->name('email-notif');
Route::get('auth/google', 'GoogleController@redirectToGoogle');
Route::get('auth/google/callback','GoogleController@handleGoogleCallback');
Route::post('/notifications/mark-read', 'NotificationController@markRead')->name('notifications.markRead');
Route::post('/notifications/mark-all-read', 'NotificationController@markAllRead')->name('notifications.markAllRead');

Route::fallback(function() {
    return view("pages.404-error");
});
Route::group(['middleware' => 'auth'], function () {
    Route::group(['middleware' => 'deactivate'], function() {
        
        // Documents
        Route::get('/dashboard/stats', 'HomeController@stats')->name('dashboard.stats');
        Route::post('change-request/confirm-password', 'RequestController@confirmPassword')->name('change-request.confirm-password');
        Route::get('/documents', 'DocumentController@index')->name('documents');
        Route::prefix('documents')->group(function() {
            Route::get('create/{id?}', 'DocumentController@create')->name('documents.create');
            Route::get('signature/{id}', 'DocumentController@signature')->name('documents.signature');
            Route::get('/folder-tree', 'DocumentController@getFolderTree');
            Route::get('/folder-view-tree/{id}', 'DocumentController@getFolderViewTree');
            Route::get('folder/{id}','DocumentController@folderView');
            Route::get('view-document/{id}','DocumentController@show');
            Route::get('/view-pdf/{id}','DocumentController@showPDF')->name('documents');
            Route::get("/visitors/{id}", "DocumentController@visitors");

            Route::post('signaturePosition','DocumentController@signaturePosition');
            Route::post('store', 'DocumentController@store')->name('documents.store');
            Route::post('store-folder','DocumentController@addFolder');
            Route::post('rename-folder/{id}','DocumentController@renameFolder');
            Route::post('delete-folder/{id}','DocumentController@deleteFolder');
            Route::post('edit_date_approved/{id}', 'DocumentController@editDateApproved');
            Route::post('upload-document-folder','DocumentController@uploadDocumentFolder');
            Route::post('upload-document','DocumentController@store')->name('documents');
            Route::get('by-control-code', 'DocumentController@getDocumentByControlCode');
            Route::post('refresh-team','DocumentController@refreshTeam');
            Route::post('bulk-delete', 'DocumentController@bulkDelete');
            Route::post("user-view", "DocumentController@userView");
            Route::post('share','DocumentController@share');
            Route::post("/share-document", "DocumentController@shareDocument");
            Route::post('/share-folder', 'DocumentController@shareFolder');
            Route::get('/next-control-code', 'DocumentController@previewNextControlCode');
        });

        Route::get('/shared-with-me', 'DocumentController@sharedWithMe')->name('shared-with-me');
        Route::get('/shared-with-others', 'DocumentController@sharedWithOthers')->name('shared-with-others');
        Route::post('/documents/revoke-access', 'DocumentController@revokeAccess')->name('documents.revoke-access');
        Route::post('/documents/revoke-folder-access', 'DocumentController@revokeFolderAccess')->name('documents.revoke-folder-access');
        Route::post('/documents/leave-share', 'DocumentController@leaveShare')->name('documents.leave-share');
        Route::post('/documents/leave-share-folder', 'DocumentController@leaveShareFolder')->name('documents.leave-share-folder');
        Route::get('/shared-with-me/folder/{id}', 'DocumentController@sharedWithMeFolderView')->name('shared-with-me.folder');
        Route::get('/shared-with-others/folder/{id}', 'DocumentController@sharedWithOthersFolderView')->name('shared-with-others.folder');
        Route::get('/documents/share-activity', 'DocumentController@shareActivity');

        Route::post('test-bulk', function() {
            return response()->json(['hit' => true]);
        });
        
         //Users
        Route::get('/users', 'UserController@index')->name('settings');
        Route::prefix('users')->group(function() {
            Route::get("access-control/{id}", "UserController@accessControl");

            Route::post('new-account', 'UserController@create')->name('settings');
            Route::post('/edit-user', 'UserController@edit_user')->name('settings');
            Route::post('/change-password', 'UserController@changepassword')->name('settings');
            Route::post('deactivate-user', 'UserController@deactivate_user')->name('settings');
            Route::post('activate-user', 'UserController@activate_user')->name('settings');
            Route::post('/access-control/update', 'UserController@updateAccessControl')->name('access-control.update');
        });

        // Type of Documents
        Route::get('/documents_type', 'TypeOfDocumentController@index')->name('settings');
        Route::prefix('documents_type')->group(function() {
            Route::get('/data', 'TypeOfDocumentController@getData')->name('document-types.data');
            Route::post('/store', 'TypeOfDocumentController@store')->name('settings');
            Route::post('/update/{id}', 'TypeOfDocumentController@update')->name('settings');
            Route::post('/delete/{id}', 'TypeOfDocumentController@destroy')->name('document-types.destroy');
        });    

        //ChangeRequest
        Route::post('change-request/record-view', 'HomeController@recordChangeRequestView')->name('change-request.record-view');
        Route::get('change-request/visitors/{id}', 'HomeController@changeRequestVisitors')->name('change-request.visitors');
        Route::post('/change-request/private-user-view', 'MonitoringController@privateUserView');
        Route::get('/change-requests','RequestController@changeRequests')->name('change-requests');
        Route::post('change-request/upload-revision/{id}', 'RequestController@uploadRevision');
        Route::post('/change-request/publish', 'PublishDocumentController@publishDocument')->name('change-request.publish');
        Route::get('/change-request/offices', 'PublishDocumentController@getOffices')->name('change-request.offices');
        Route::prefix('change-request')->group(function() {
            Route::get('/data', 'RequestController@getChangeRequestsData')->name('change-requests.data');
            Route::get('for_approval/{id}', 'RequestController@show');
            Route::get('view-change-request/{id}','RequestController@viewChangeRequest');
            Route::get('get-comments','RequestController@getComments');

            Route::post('store','RequestController@store');
            Route::post('comments', 'RequestController@comments');
            Route::post('change-request-action/{id}','RequestController@action');
            Route::post('confirm-password','RequestController@confirmPassword');
            Route::post("upload-sign-documents",'RequestController@uploadSignDocuments');
        });

        // Copy Request
        Route::prefix('copy_request')->group(function() {
            Route::post('/store','CopyController@store');
            Route::post('copy-request-action/{id}','CopyController@action');
        });

        // Approver stamp
        Route::get('/approver-stamp', 'ApproverStampController@index');
        Route::prefix('approver-stamp')->group(function() {
            Route::get('/data', 'ApproverStampController@getData')->name('approver-stamp.data');
            Route::post('/store', 'ApproverStampController@store');
        });

        // Roles & Permissions
        Route::get('/roles', 'RoleController@index')->name('settings');
        Route::prefix('roles')->group(function() {
            Route::get('/data', 'RoleController@getData')->name('roles.data');
            Route::get('/edit', 'RoleController@edit');
            Route::post('/store', 'RoleController@store')->name('roles.store');
            Route::post('/update', 'RoleController@update');
            // Route::get('/permissions/data', 'RoleController@getPermissionsData')->name('permissions.data');
            // Route::post('/permissions/store', 'RoleController@storePermission')->name('permissions.store');
            // Route::post('/add-permission/{id}', 'RoleController@addPermission')->name('roles.addPermission');
        });

        Route::prefix('control-codes')->group(function () {
            Route::get('/data',         'ControlCodeController@getData')->name('control-codes.data');
            Route::post('/store',       'ControlCodeController@store')->name('control-codes.store');
            Route::post('/update/{id}', 'ControlCodeController@update')->name('control-codes.update');
            Route::post('/delete/{id}', 'ControlCodeController@destroy')->name('control-codes.destroy');
        });

        Route::get('/system-configuration', 'SystemConfigurationController@index')->name('settings');

        Route::prefix('system-configuration')->group(function () {
            Route::get('/campus/data', 'SystemConfigurationController@getCampusData')->name('system-config.campus.data');
            Route::get('/departments/data', 'SystemConfigurationController@getDepartmentsData')->name('system-config.departments.data');
            Route::get('/teams/data', 'SystemConfigurationController@getTeamsData')->name('system-config.teams.data');
            Route::get('/document-types/data', 'SystemConfigurationController@getDocumentTypesData')->name('system-config.document-types.data');
            Route::get('/control-codes/data', 'SystemConfigurationController@getControlCodesData')->name('system-config.control-codes.data');
            Route::get('/tags/data', 'SystemConfigurationController@getTagsData')->name('system-config.tags.data');
        });

        Route::get('/access-control', 'AccessControlController@index')->name('settings');
        Route::post('/access-control/update', 'AccessControlController@update')->name('access-control.update');
        
        Route::prefix('permission')->group(function() {
            Route::post('/store','PermissionController@store');
            Route::post('/update/{id}','PermissionController@update');
        });

        // Permits
        Route::get('/permits', 'PermitController@index')->name('permits');
        Route::prefix('permits')->group(function() {
            Route::get('/data', 'PermitController@getData')->name('permits.data');
            Route::post('/store', 'PermitController@store')->name('permits');
            Route::post('/upload/{id}', 'PermitController@upload')->name('permits');
            Route::post('change-type/{id}', 'PermitController@change_type')->name('permits');
        });

        Route::prefix('campus')->group(function() {
            Route::post('/store', 'CampusController@storeCampus')->name('campus.store');
            Route::post('update/{id}', 'CampusController@updateCampus')->name('campus.update');
            Route::delete('/{id}', 'CampusController@destroyCampus')->name('campus.destroy');

            //for offices
            Route::get('/list', 'CampusController@getCampusList')->name('campus.list');
        });

        Route::prefix('tags')->group(function() {
            Route::post('/store', 'TagController@store')->name('tags.store');
            Route::post('update/{id}', 'TagController@update')->name('tags.update');
            Route::delete('/{id}', 'TagController@destroy')->name('tags.destroy');
            Route::get('/list', 'TagController@getList')->name('tags.list');
        });

        // Departments
        Route::get('/departments', 'DepartmentController@index')->name('settings');
        Route::prefix('departments')->group(function() {
            Route::get('/data', 'DepartmentController@getData')->name('departments.data');
            Route::post('/store', 'DepartmentController@store')->name('settings');
            Route::post('/update/{id}', 'DepartmentController@update')->name('settings');
            // Route::post('/deactivate', 'DepartmentController@deactivate')->name('settings');
            // Route::post('/activate', 'DepartmentController@activate')->name('settings');
            Route::delete('/{id}', 'DepartmentController@destroy')->name('departments.destroy');
        });

        // Office
        Route::get("offices", "OfficeController@index");
        Route::prefix("offices")->group(function() {
            Route::post("/store", "OfficeController@store");
            Route::post("/update/{id}", "OfficeController@update");
            Route::post('/deactivate', 'OfficeController@deactivate');
            Route::post('/activate', 'OfficeController@activate');
        });

        // Teams
        Route::get('/teams', 'TeamsController@index')->name('settings');
        Route::prefix('teams')->group(function() {
            Route::get('/data', 'TeamsController@getData')->name('teams.data');
            Route::post('/', 'TeamsController@store')->name('teams.store');
            Route::put('/{id}', 'TeamsController@update')->name('teams.update');
            Route::delete('/{id}', 'TeamsController@destroy')->name('teams.destroy');
            Route::post('/deactivate', 'TeamsController@deactivate');
            Route::post('/activate', 'TeamsController@activate');
            Route::delete('/{id}', 'TeamsController@destroy')->name('teams.destroy');
        });

        // Route::post('/change-department/{id}', 'PermitController@update')->name('permits');
        // Route::post('inactive-permits/{id}', 'PermitController@inactivePermits');
        // Route::post('activate-permits/{id}', 'PermitController@activatePermits');

        // Home
        Route::get('/', 'HomeController@index')->name('home')->middleware('role');
        Route::get('/home', 'HomeController@index')->name('home')->middleware('role');
        Route::get('/search', 'HomeController@search')->name('search');
        Route::post("/request_access/{id}","HomeController@requestAccess");
        Route::post("/request_access_approved/{id}","HomeController@requestAccessApproved");
        Route::post("/request_access_declined/{id}","HomeController@requestAccessDeclined");
        Route::get('/for-request-access', 'HomeController@forRequestAccess')->name('for-request-access');
        Route::get('/request_access/data', 'HomeController@getRequestAccessData')->name('request_access.data');

        Route::get('/monitoring', 'MonitoringController@index')->name('monitoring');
    
        Route::get('/request', 'RequestController@index')->name('requests');
        Route::post('change-request-edit/{id}','RequestController@editRequest')->name('change-requests');
        Route::get('/for-approval','RequestController@forApproval')->name('for-approval');
        Route::get('/for-approval/change/data', 'RequestController@getChangeApprovalsData')->name('for-approval.change.data');
        Route::get('/for-approval/copy/data',   'RequestController@getCopyApprovalsData')->name('for-approval.copy.data');
        Route::post('/edit-title/{id}','RequestController@editTile');
    
        Route::post('/upload-file/{id}', 'DocumentController@upload')->name('documents');
        Route::post('view-document/edit-document/{id}','DocumentController@edit');
        Route::get('audits','DocumentController@audit')->name('audit');
    
        // Route::get('/companies', 'CompanyController@index')->name('settings');
        // Route::post('/new-company', 'CompanyController@store')->name('settings');
        // Route::post('deactivate-company', 'CompanyController@deactivate')->name('settings');
        // Route::post('activate-company', 'CompanyController@activate')->name('settings');
    
        // Departments
        // Route::get('/departments', 'DepartmentController@index')->name('settings');
        // Route::post('/new-department', 'DepartmentController@store')->name('settings');
        // Route::post('deactivate-department', 'DepartmentController@deactivate')->name('settings');
        // Route::post('activate-department', 'DepartmentController@activate')->name('settings');
        // Route::post('edit-department/{id}','DepartmentController@update')->name('settings');
    
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

    Route::get('/reports', 'ReportController@index')->name('reports');
    Route::prefix('reports')->group(function () {
        Route::get('/summary-stats',      'ReportController@getSummaryStats')->name('reports.summary-stats');
        Route::get('/change-requests',    'ReportController@getChangeRequestsReport')->name('reports.change-requests');
        Route::get('/documents',          'ReportController@getDocumentsReport')->name('reports.documents');
        Route::get('/approver-activity',  'ReportController@getApproverActivityReport')->name('reports.approver-activity');
        Route::get('/department-summary', 'ReportController@getDepartmentSummaryReport')->name('reports.department-summary');
        Route::get('/export-csv',         'ReportController@exportCsv')->name('reports.export-csv');
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

// Request OTP
Route::get('password/reset', function () { return view('auth.passwords.email'); })->name('password.request');
Route::post('password/reset', [ForgotPasswordOtpController::class, 'sendOtp'])->name('password.email');

Route::get('password/otp', function () { return view('auth.passwords.otp'); })->name('password.otp');
Route::post('password/otp', [ForgotPasswordOtpController::class, 'verifyOtp'])->name('password.verify');

Route::get('password/custom-reset', function () { return view('auth.passwords.reset'); })->name('password.customreset');
Route::post('password/custom-reset', [ForgotPasswordOtpController::class, 'resetPassword'])->name('password.reset.final');

// Route::get('mailable', function () {
//     $documents = App\Document::find(1);
//     $request = $documents->change_requests->sortByDesc('id')->first();
//     $approver = $request->approvers->first();
//     // dd($approver);
//     // $approver = App\RequestApprover::where()->find(1);
 
//     // return new App\Mail\RequestDocumentApproval($change_request,$user);
//     return new App\Mail\ApprovedDateEmail($documents,$approver);
// });