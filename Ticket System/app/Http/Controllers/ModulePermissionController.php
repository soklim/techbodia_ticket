<?php

namespace App\Http\Controllers;

use App\Models\ModulePermission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Hash;

class ModulePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rolde_id = Auth::user()->role_id;
        $module_id = 13;
        $permission = DB::table('module_permissions')->where('role_id', $rolde_id)->where('module_id', $module_id)->first();
        if ($permission->a_read != 1){
            return view('error.error404');
        }
        else{
            $role = Role::all();
            $module = DB::table("modules as m")
                ->join("group_modules as g", function($join){
                    $join->on("m.group_id", "=", "g.id");
                })
                ->select("g.name as group_module_name", "m.name as module_name")
                ->where("m.id", "=", $module_id)
                ->get();
            return view('module_permissions.index',[
                'role' => $role,
                'module' => $module
            ]);
        }
    }

    public function  getData(Request $request){

        $data = DB::table("modules as m")
            ->leftJoin("module_permissions as p", function($join) use ($request) {
                $join->on("m.id", "=", "p.module_id")
                ->where("p.role_id", "=", $request->role_id);
            })
            ->select(DB::raw("IFNULL(p.id,0) as permission_id")
                , "m.id as module_id"
                , "m.name as module_name"
                , DB::raw("IFNULL(p.a_read,0) as a_read")
                , DB::raw("IFNULL(p.a_create,0) as a_create")
                , DB::raw("IFNULL(p.a_update,0) as a_update")
                , DB::raw("IFNULL(p.a_delete,0) as a_delete"))
            ->get();


        return response()->json($data->toArray());

    }

    public function Save(Request $request)
    {
        try{
            $input = $request->json()->all()["permission"];
            foreach($input as $item) { //foreach element in $arr
                $inputs['module_id'] = $item['module_id'];
                $inputs['role_id'] = $item['role_id'];
                $inputs['a_read'] = $item['a_read'];
                $inputs['a_create'] = $item['a_create'];
                $inputs['a_update'] = $item['a_update'];
                $inputs['a_delete'] = $item['a_delete'];

                if($item['permission_id'] == 0){
                    ModulePermission::create($inputs);
                }
                else{

                    $data = ModulePermission::where('id',$item['permission_id']);
                    $data->update($inputs);
                }
            }

            return Response()->json(array(
                'code' => 0
            ));
        }
        catch (Exception $e){
            return Response()->json(array(
                'code' => 1,"msg"=>$e->getMessage()
            ));
        }

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ModulePermission  $modulePermission
     * @return \Illuminate\Http\Response
     */
    public function show(ModulePermission $modulePermission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ModulePermission  $modulePermission
     * @return \Illuminate\Http\Response
     */
    public function edit(ModulePermission $modulePermission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ModulePermission  $modulePermission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ModulePermission $modulePermission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ModulePermission  $modulePermission
     * @return \Illuminate\Http\Response
     */
    public function destroy(ModulePermission $modulePermission)
    {
        //
    }
}
