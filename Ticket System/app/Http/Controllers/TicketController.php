<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Hash;
use Carbon\Carbon;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role_id = Auth::user()->role_id;
        $module_id = 24;
        $permission = DB::table('module_permissions')->where('role_id', $role_id)->where('module_id', $module_id)->first();
        if ($permission->a_read != 1){
            return view('error.error404');
        }
        else{
            $ticket_type = DB::select("SELECT DISTINCT id as type_id,ticket_type_name as name FROM ticket_types t 
            WHERE t.id IN(SELECT a.ticket_type_id FROM assign_ticket_types a WHERE a.role_id=$role_id AND a.can_create=1)");   
           
            $module = DB::table("modules as m")
            ->join("group_modules as g", function($join){
                $join->on("m.group_id", "=", "g.id");
            })
            ->select("g.name as group_module_name", "m.name as module_name")
            ->where("m.id", "=", $module_id)
            ->get();

            return view('ticket.index',[
                'ticket_type' => $ticket_type,
                'module'=>$module,
                'role_id'=>$role_id,
                'permission'=>$permission
            ]);
        }
    }

    public function Save(Request $request)
    {
        try{
            $request->validate([
                'id' => 'required',
                'summary' => 'required',               
                'description' => 'required',
                'ticket_type_id' => 'required',
                'severity' => 'required',
                'priority' => 'required',
            ]);

            $current_date_time = Carbon::now()->toDateTimeString();
            if ($request->id == 0){
                $input['summary'] = $request->summary;
                $input['description'] = $request->description;           
                $input['status_id'] = 1;
                $input['ticket_type_id'] = $request->ticket_type_id;
                $input['created_by'] = Auth::user()->id;
                $input['created_date'] = $current_date_time;
                $input['severity'] = $request->severity;
                $input['priority'] = $request->priority;


                Ticket::create($input);
            }
            else{
                $input = $request->all();
                $data = Ticket::find($request->id);
                $data->update($input);
            }
            return Response()->json(array(
                'code' => 0,
            ));
        }
        catch (\Exception $e) {
            return Response()->json(array(
                'code' => 1,
                'message'=>$e->getMessage()
            ));
        }
        
    }

    public function Create_Ticket_API(Request $request)
    {
        try{
            dd(Auth::guard('api')->check());
            $request->validate([
                'summary' => 'required',               
                'description' => 'required',
                'ticket_type_id' => 'required',
                'severity' => 'required',
                'priority' => 'required',
            ]);

            $current_date_time = Carbon::now()->toDateTimeString();
            $input['summary'] = $request->summary;
            $input['description'] = $request->description;           
            $input['status_id'] = 1;
            $input['ticket_type_id'] = $request->ticket_type_id;
            $input['created_by'] = 1;
            $input['created_date'] = $current_date_time;
            $input['severity'] = $request->severity;
            $input['priority'] = $request->priority;

            Ticket::create($input);
            return Response()->json(array(
                'code' => 0,
            ));
        }
        catch (\Exception $e) {
            return Response()->json(array(
                'code' => 1,
                'message'=>$e->getMessage()
            ));
        }
        
    }

    public function  getData(){

        $role_id = Auth::user()->role_id;
        $user_id = Auth::user()->id;

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
                                ,CASE WHEN $role_id=1 THEN 1 ELSE CASE WHEN t.created_by = $user_id THEN 1 ELSE 0 END END is_owner
                                ,(SELECT COUNT(ts.id) FROM assign_ticket_types ts WHERE ts.ticket_type_id=t.ticket_type_id AND ts.role_id=$role_id AND ts.can_resolve=1) as can_resolved
                                FROM tickets t
                                INNER JOIN ticket_types t1 ON t.ticket_type_id = t1.id
                                LEFT JOIN statuss s ON t.status_id = s.id
                                WHERE t.status_id IN (1,2,3) ORDER BY t.id DESC");
        return response()->json($data);

    }

    public function Delete(Request $request)
    {
        try{
            $current_date_time = Carbon::now()->toDateTimeString();
            $input['deleted_by'] = Auth::user()->id;
            $input['deleted_date'] = $current_date_time;
            $input['status_id'] = 3;
            $data = Ticket::find($request->id);
            $data->update($input);
            return Response()->json(array(
                'code' => 0,
            ));
        }
        catch (\Exception $e) {
            return Response()->json(array(
                'code' => 1,
                'message'=>$e->getMessage()
            ));
        }
       
    }
    public function Resolve(Request $request)
    {
        try{
            $current_date_time = Carbon::now()->toDateTimeString();
            $input['resolved_by'] = Auth::user()->id;
            $input['resolved_date'] = $current_date_time;
            $input['status_id'] = 2;
            $data = Ticket::find($request->id);
            $data->update($input);
            return Response()->json(array(
                'code' => 0,
            ));
        }
        catch (\Exception $e) {
            return Response()->json(array(
                'code' => 1,
                'message'=>$e->getMessage()
            ));
        }
       
    }
   
}
