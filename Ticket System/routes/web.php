<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\GroupModuleController;
use App\Http\Controllers\ModulePermissionController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketTypeController;
use App\Http\Controllers\AssignTicketTypeController;


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

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get("/error404", function(){
    return View::make("error.error404");
});

Route::group(['middleware' => ['auth']], function() {

    //User
    Route::resource('users', UserController::class);
    Route::get('/UserGetData',[UserController::class, 'getData'])->name('users.GetData');
    Route::get('/UserGetInitPage',[UserController::class, 'getInitPage'])->name('users.GetInitPage');
    Route::post('/UserSave',[UserController::class, 'Save'])->name('users.Save');
    Route::post('/UserUpdateActive',[UserController::class, 'Update'])->name('users.Update');
    Route::post('/ResetPassword',[UserController::class, 'ResetPassword'])->name('users.ResetPassword');
    Route::get('/user/change_password',[UserController::class, 'ChangePassword'])->name('users.change_password');
    Route::get('/user/profile',[UserController::class, 'Profile'])->name('users.profile');
    Route::post('/change-password', [UserController::class, 'updatePassword'])->name('update-password');
    Route::post('/user/updateProfile', [UserController::class, 'updateProfile'])->name('users.updateProfile');
    Route::post('/change_lan',[UserController::class, 'ChangeLan'])->name('user.change_lan');

    //Role
    Route::resource('roles', RoleController::class);
    Route::get('/RoleGetData',[RoleController::class, 'getData'])->name('roles.GetData');
    Route::post('/RoleSave',[RoleController::class, 'Save'])->name('roles.Save');
    Route::post('/RoleDelete',[RoleController::class, 'Delete'])->name('roles.Delete');
     
    //Group Module
    Route::resource('group_modules', GroupModuleController::class);
    Route::get('/GroupModuleGetData',[GroupModuleController::class, 'getData'])->name('group_modules.GetData');
    Route::post('/GroupModuleSave',[GroupModuleController::class, 'Save'])->name('group_modules.Save');
    Route::post('/GroupModuleDelete',[GroupModuleController::class, 'Delete'])->name('group_modules.Delete');

     //Ticket
    Route::resource('ticket', TicketController::class);
    Route::get('/TicketGetData',[TicketController::class, 'getData'])->name('ticket.GetData');
    Route::post('/TicketSave',[TicketController::class, 'Save'])->name('ticket.Save');
    Route::post('/TicketDelete',[TicketController::class, 'Delete'])->name('ticket.Delete');
    Route::post('/TicketResolve',[TicketController::class, 'Resolve'])->name('ticket.Resolve');

     //TicketType
     Route::resource('ticket_type', TicketTypeController::class);
     Route::get('/TicketTypeGetData',[TicketTypeController::class, 'getData'])->name('ticket_type.GetData');
     Route::post('/TicketTypeSave',[TicketTypeController::class, 'Save'])->name('ticket_type.Save');
     Route::post('/TicketTypeDelete',[TicketTypeController::class, 'Delete'])->name('ticket_type.Delete');

     //AssignTicketType
     Route::resource('assign_ticket_type', AssignTicketTypeController::class);
     Route::get('/AssignTicketTypeGetData',[AssignTicketTypeController::class, 'getData'])->name('assign_ticket_type.GetData');
     Route::post('/AssignTicketTypeSave',[AssignTicketTypeController::class, 'Save'])->name('assign_ticket_type.Save');
     Route::post('/AssignTicketTypeDelete',[AssignTicketTypeController::class, 'Delete'])->name('assign_ticket_type.Delete');
     
    //Module
    Route::resource('modules', ModuleController::class);
    Route::get('/ModuleGetData',[ModuleController::class, 'getData'])->name('modules.GetData');
    Route::post('/ModuleSave',[ModuleController::class, 'Save'])->name('modules.Save');
    Route::post('/ModuleDelete',[ModuleController::class, 'Delete'])->name('modules.Delete');

    //Permission
    Route::resource('module_permissions', ModulePermissionController::class);
    Route::get('/PermissionGetData',[ModulePermissionController::class, 'getData'])->name('module_permissions.GetData');
    Route::post('/PermissionSave',[ModulePermissionController::class, 'Save'])->name('module_permissions.Save');

 
    Route::get('language/{locale}', function ($locale) {
        app()->setLocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    });
   
});

