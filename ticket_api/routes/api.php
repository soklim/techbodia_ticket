<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('ticket',function(){
        
    $data = DB::select("SELECT t.id,t.summary,t.description
                ,IFNULL((SELECT name FROM users WHERE id=t.created_by),'') as created_by
                ,IFNULL(t.created_date,'') as created_date
                ,IFNULL((SELECT name FROM users WHERE id=t.resolved_by),'') as resolved_by
                ,IFNULL(t.resolved_date,'') as resolved_date
                ,IFNULL((SELECT name FROM users WHERE id=t.deleted_by),'') as deleted_by
                ,IFNULL(t.deleted_date,'') as deleted_date
                ,t1.ticket_type_name,t.ticket_type_id
                ,t.status_id
                ,s.status_name
                ,IFNULL(t.priority,0) as priority,IFNULL(t.severity,0) as severity
                FROM tickets t
                INNER JOIN ticket_types t1 ON t.ticket_type_id = t1.id
                LEFT JOIN statuss s ON t.status_id = s.id
                WHERE t.status_id IN (1,2,3)");
    return response()->json([
        'status' => 'success',
        'data' => $data
    
    ]);
})->middleware('auth:api');

Route::post('create_ticket',[App\Http\Controllers\TicketController::class, 'Create_Ticket_API'])->middleware('auth:api');

/// login
Route::post('login', [App\Http\Controllers\UserAuthController::class, 'login']);
