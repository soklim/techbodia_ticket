<?php

namespace App\Http\Controllers;

use App\Models\AssignTicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Hash;


class AssignTicketTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rolde_id = Auth::user()->role_id;
        $module_id = 26;
        $permission = DB::table('module_permissions')->where('role_id', $rolde_id)->where('module_id', $module_id)->first();
        if ($permission->a_read != 1){
            return view('error.error404');
        }
        else{
            $module = DB::table("modules as m")
            ->join("group_modules as g", function($join){
                $join->on("m.group_id", "=", "g.id");
            })
            ->select("g.name as group_module_name", "m.name as module_name")
            ->where("m.id", "=", $module_id)
            ->get();

            $role = DB::table('roles')
            ->select('id', 'name')
            ->get();

            $ticket_type = DB::table('ticket_types')
            ->select('id', 'ticket_type_name as name')
            ->get();

            return view('assign_ticket_types.index',[
                'module' => $module,
                'permission'=>$permission,
                'role' => $role,
                'ticket_type' => $ticket_type,
            ]);
        }
    }
    public function Save(Request $request)
    {
        try{
            if ($request->id == 0){
                $input['ticket_type_id'] = $request->ticket_type_id;
                $input['role_id'] = $request->role_id;
                $input['can_create'] = $request->can_create;
                $input['can_resolve'] = $request->can_resolve;
                AssignTicketType::create($input);
            }
            else{
                $input = $request->all();
                $role = AssignTicketType::find($request->id);
                $role->update($input);
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
    public function  getData(Request $request){

        $role_id = $request->role_id;
        $ticket_type_id = $request->ticket_type_id;

        $data = DB::select("SELECT a.id,a.ticket_type_id,a.role_id,a.can_create,a.can_resolve,t.ticket_type_name,r.`name` as role_name 
        FROM assign_ticket_Types a 
        INNER JOIN ticket_types t ON a.ticket_type_id = t.id
        INNER JOIN roles r ON a.role_id =r.id
        WHERE (a.role_id = $role_id OR $role_id=0) AND (a.ticket_type_id = $ticket_type_id OR $ticket_type_id=0)");
        return response()->json($data);

    }

    public function Delete(Request $request)
    {
        try{
            $id = $request->id;
            DB::delete('delete from assign_ticket_Types where id = ?',[$id]);
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
