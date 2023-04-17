<?php

namespace App\Http\Controllers;

use App\Models\GroupModule;
use App\Models\ModulePermission;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Hash;
use PHPUnit\TextUI\XmlConfiguration\Group;

class GroupModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rolde_id = Auth::user()->role_id;
        $module_id = 3;
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
            return view('group_modules.index',[
                'module' => $module
            ]);
        }
    }

    public function Save(Request $request)
    {
        if ($request->id == 0){
            $input['name'] = $request->name;
            $input['icon'] = $request->icon;
            $input['route'] = $request->route;
            GroupModule::create($input);
        }
        else{
            $input = $request->all();
            $data = GroupModule::find($request->id);
            $data->update($input);
        }
        return Response()->json(array(
            'code' => 0,
        ));
    }
    public function  getData(){

        $data = DB::table('group_modules')
            ->select('group_modules.*')
            ->get();
        return response()->json($data->toArray());

    }
    public function Delete(Request $request)
    {
        try{
            $id = $request->id;
            DB::delete('delete from group_modules where id = ?',[$id]);
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
     * @param  \App\Models\GroupModule  $groupModule
     * @return \Illuminate\Http\Response
     */
    public function show(GroupModule $groupModule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GroupModule  $groupModule
     * @return \Illuminate\Http\Response
     */
    public function edit(GroupModule $groupModule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GroupModule  $groupModule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GroupModule $groupModule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GroupModule  $groupModule
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroupModule $groupModule)
    {
        //
    }
}
