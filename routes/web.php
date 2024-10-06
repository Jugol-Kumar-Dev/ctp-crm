<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AutorizaitonController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\ExpanceController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\HostingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\MethodController;
use App\Http\Controllers\NoteCategoryController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\BusinessSettingController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PurposeController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\QuotationInvoice;
use App\Http\Controllers\SearviceController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\WorkingSummeryController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PackageController;
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

Route::get('/', [LoginController::class, 'login'])->middleware('guest')->name('login');

Route::post('/login-as', [LoginController::class, 'loginAs']);

Route::prefix('admin')->group(function(){
    Route::middleware('guest')->group(function (){
        Route::get('login', [LoginController::class, 'login'])->name('login');
        Route::post('login', [LoginController::class, 'authenticate']);
    });

    Route::middleware(['auth', 'web'])->group(function(){
        // dashboard management
        Route::get('dashboard', DashboardController::class);
        // user management
        Route::resource('users', AdminController::class);
        Route::get('all-users', [AdminController::class, 'allUsers']);
        Route::post('users/{id}', [AdminController::class, 'update']);
        Route::post('user/update-pass/{id}', [AdminController::class, 'updateCredentials'])->name('updateCredentials');
        Route::post('user/update-profile-image', [AdminController::class, 'uploadProfile'])->name('users.uploadProfile');


        // user work summery
        Route::get('/work-summery', [WorkingSummeryController::class, 'index'])->name('user.workSummery');
        Route::get('/admin-show-summery', [WorkingSummeryController::class, 'adminIndex'])->name('user.adminIndex');
        Route::post('/work-summery/save', [WorkingSummeryController::class, 'store'])->name('user.workSummery');



        // user authorizations
        Route::resource('authorizations', AutorizaitonController::class);
        // clients management
        Route::get('clients', [ClientsController::class, 'index']);
        Route::resource('clients', ClientsController::class);
        Route::post('clients/set-follow-up', [ClientsController::class, 'setFollowUp'])->name('client.setFollowUp');
        Route::post('clients/bulk-status/update', [ClientsController::class, 'bulkStatusUpdate'])->name('lead.bulkStatusUpdate');
        Route::post('clients/bulk-assigned/update', [ClientsController::class, 'bulkStatusAssigned'])->name('lead.bulkStatusAssigned');

        // leads management
        Route::resource('leads', LeadController::class);
        Route::get('show-lead/{id}', [LeadController::class, 'show']);

        // designs management
        Route::resource('designs', DesignController::class);
        // services management
        Route::resource('services', SearviceController::class);
        // service packages
        Route::post('services/create-package', [SearviceController::class, 'createPackage'])->name('createPackage');
        Route::get('services/edit-package/{id}', [SearviceController::class, 'editPackage'])->name('editPackage');
        Route::put('services/update-package/{id}', [SearviceController::class, 'updatePackage'])->name('updatePackage');
        Route::delete('services/delete-package/{id}', [SearviceController::class, 'deletePackage'])->name('deletePackage');


        // services featured
        Route::post('services/create-feature', [SearviceController::class, 'createFeature'])->name('createFeature');
        Route::get('services/edit-feature/{id}', [SearviceController::class, 'editFeature'])->name('editFeature');
        Route::put('services/update-feature/{id}', [SearviceController::class, 'updateFeature'])->name('updateFeature');
        Route::delete('services/delete-feature/{id}', [SearviceController::class, 'deleteFeature'])->name('deleteFeature');

        // platforms management
        Route::resource('platforms', PlatformController::class);
        // package management
        Route::resource('package', PackageController::class);
        // features management
        Route::resource('features', FeatureController::class);
        // works management
        Route::resource('works', WorkController::class);
        // domains management
        Route::resource('domains', DomainController::class);
        // hostings management
        Route::resource('hostings', HostingController::class);
        // quotations management
        Route::resource('quotations', QuotationController::class);
        Route::post('quotations/given-discount/{id}', [QuotationController::class, 'givenDiscount'])->name('quotations.addDiscount');

        Route::get('download-quotation/{id}', [QuotationController::class, 'generateQuotationPDFFile'])
            ->name('quotations.generateQuotationPDFFile');

        Route::get('download/quotation-invoice/{id}', [QuotationController::class, 'createInvoice'])
            ->name('quotation.download');

        Route::post('/send-quotation-email/{id?}', [QuotationController::class, 'sendMail'])->name('quotations.sendCustom.email');
        Route::get('edit/quotation/{id}', [QuotationController::class, 'editQuotation'])->name('quotations.edit');
        Route::post('quotation/update-status', [QuotationController::class, 'chnageQuotationStatus'])->name('chnageQuotationStatus');
        Route::get('quotations-to-invoice/{id}', [QuotationController::class, 'quotationInvoice'])->name('quotations.quotationInvoice');
        Route::post('quotations-invoice-payment', [QuotationController::class, 'addPayment'])->name('quotations.addPayment');
        Route::get('quotations-send-by-email/{id}', [QuotationController::class, 'sendMail'])->name('quotations.sendMail');


        // invoices management
        Route::resource('invoices', InvoiceController::class);
        Route::post('invoice/quotation/{id}', [InvoiceController::class, 'createInvoice'])->name('invoices.createInvoice');
        Route::get('invoice/download/{id}', [InvoiceController::class, 'downloadInvoice'])->name('invoices.downloadInvoice');
        Route::post('invoices/given-discount/{id}', [InvoiceController::class, 'addDiscount'])->name('invoices.addDiscount');
        Route::post('/send-invoice-email/{id?}', [InvoiceController::class, 'sendMail'])->name('invoices.sendCustom.email');


        Route::get('edit/invoice/{id}', [InvoiceController::class, 'edit'])->name('invoices.edit');
        Route::get('download-invoice/{id}', [InvoiceController::class, 'generateInvoicePDFFile'])->name('invoices.generateInvoicePDFFile');
        Route::delete('edit/invoice/{id}', [InvoiceController::class, 'destroy'])->name('invoices.delete');
        Route::patch('update/invoice/{id}',[InvoiceController::class, 'updateInvoice'])->name('updateInvoices');
        Route::post('invoice/custom/transaction', [InvoiceController::class, 'addPayment'])->name('saveInvoiceTransaction');


        // method management
        Route::resource('methods', MethodController::class);
        // purposes management
        Route::resource('purposes', PurposeController::class);
        // projects management
        Route::resource('projects', ProjectController::class);
        Route::post('project/assign-developers', [ProjectController::class, 'assignDevelopers'])->name('projects.assignDevelopers');
        Route::get('project/remove-user', [ProjectController::class, 'removeUser'])->name('projects.removeUser');
        Route::post('projects/{id}', [ProjectController::class, 'update']);
        Route::get('project/employee-projects', [ProjectController::class, 'employeeProjects'])->name('employeeProject');
        Route::post('project/change-status', [ProjectController::class, 'updateProgress'])->name('projects.updateProgress');
        Route::post('project/update-details/{id}', [ProjectController::class, 'updateProjectDetails'])->name('projects.updateProjectDetails');
        Route::post('project/update-project-backup/{id}', [ProjectController::class, 'updateProjectBackup'])->name('projects.updateProjectBackup');
        Route::post('project/update-attachment/{id}', [ProjectController::class, 'updateProjectAttachment'])->name('projects.updateProjectAttachment');

        Route::get('project/download-attachment/{path?}', function(){
            $path = request()->input('path');
            if(!empty($path) && $path != 'null'){
                return \Illuminate\Support\Facades\Storage::disk('public')->download($path);
                return \Illuminate\Support\Facades\Storage::disk('public')->download(request()->input('path'));
            }
            return back();
        });


        // transaction management
        Route::resource('transaction', TransactionController::class);
        Route::post('quotation/transaction', [TransactionController::class, 'saveQuotationTransaction'])->name('saveQuotationTransaction');
        // due transaction management
        Route::get('/due-report', [TransactionController::class, 'getDueTransactions'])->name('dueTransactions');

        //expanse management
        Route::resource('expense', ExpanceController::class);
        Route::post('update-expance/{id}', [ExpanceController::class, 'update']);
        Route::resource('chat', ChatController::class);
        // note category management
        Route::resource('notes-category', NoteCategoryController::class);
        // note management
        Route::resource('notes', NoteController::class);
        Route::post('notes-update', [NoteController::class, 'update'])->name('notes.update');
        Route::get('employee-notes', [NoteController::class, 'employeeNotes'])->name('notes.empNots');

        // todo management
        Route::resource('todos', TodoController::class);
        Route::get('change-todo-status/{id}', [TodoController::class, 'show']);
        Route::post('todos/replay-todo', [TodoController::class, 'replayTodo'])->name('replayTodo');
        Route::get('my-todos', [TodoController::class, 'myTodos'])->name('myTodos');
        Route::get('complete-todos', [TodoController::class, 'completeTodos'])->name('completeTodos');
        Route::get('read-all-notification', [TodoController::class, 'readAllNotification'])->name('readAllNotification');

        // old invoice and quotation management
        Route::get('old-quotation', [\App\Http\Controllers\OldDataController::class, 'quotation'])->name('oldQuotation');
        Route::get('old-quotation/{id}', [\App\Http\Controllers\OldDataController::class, 'showQuotation'])->name('showQuotation');
        Route::get('old-invoice', [\App\Http\Controllers\OldDataController::class, 'invoice'])->name('oldInvoice');;
        Route::get('old-invoice/{id}', [\App\Http\Controllers\OldDataController::class, 'showInvoice'])->name('showInvoice');;


        // update smtp setup
        Route::get('business-settings', [BusinessSettingController::class, 'index'])->name('businessIndex');
        Route::post('business-settings/all-settings', [BusinessSettingController::class, 'updateSettings'])->name('businessIndex.updateSettings');
        Route::post('mail-setup', [BusinessSettingController::class, 'updateSmtp'])->name('updateSmtp');

        Route::get('/all-policy-settings', [BusinessSettingController::class, 'getAllPolicy']);


        // activity log management
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('log.index');
    });

    Route::any('/logout', [LoginController::class, 'destroy']);
});

Route::get('/qut-email', [TestController::class, 'testEmail']);
Route::get('invoice-show/{id}',  [QuotationController::class, 'createInvoice']);


Route::get('/test', [\App\Http\Controllers\TestController::class, 'index'])->name('test.index');
Route::post('/test/create', [\App\Http\Controllers\TestController::class, 'create'])->name('test.create');
Route::get('/test/edit/{id}', [\App\Http\Controllers\TestController::class, 'edit'])->name('test.edit');
Route::put('/test/update/{id}', [\App\Http\Controllers\TestController::class, 'update'])->name('test.update');


// check unique lead customer number exising
Route::get('/check-phone-unique', [ClientsController::class, 'checkNumberUnique']);


Route::get("/test", function(){
    return inertia("ntest");
});


Route::get('/storage',function (){
    Artisan::call('storage:link');
});

