<?php

namespace App\Http\Controllers;

use App\Models\ModulePermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use App;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        $rolde_id = Auth::user()->role_id;
        $module_id = 2;
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

            return view('users.index',[
                'module' => $module
            ]);
        }

    }

    public function profile(Request $request)
    {
        $userId = Auth::user()->id;
        $user = DB::select("SELECT u.name,u.username,u.phone,u.sex, IFNULL(u.image,'/assets/images/user.png') as image FROM users u WHERE u.id =$userId");

        $sex =DB::table('setting_items')
        ->select('item_id', 'name_kh')
        ->where('type_id','=',1)
        ->get();
        return view('users.profile',[
            'user' => $user,
            'sex' => $sex
        ]);

    }

    public function ChangePassword(Request $request)
    {
        return view('users.change_password',[
        ]);

    }

    public function updatePassword(Request $request)
    {
        # Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);


        #Match The Old Password
        if(!Hash::check($request->old_password, auth()->user()->password)){
            return back()->with("error", "Old Password Doesn't match!");
        }


        #Update the new Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)          
        ]);

        return back()->with("status", "Password changed successfully!");
    }

    public function updateProfile(Request $request)
    {
        try{
            
            if($request->file('image') != null){
                
                // $image_name = time().'.'.$request->file('image')->getClientOriginalExtension();
                $filename = time() . '.' . $request->image->extension();
                // $request->file('image')->move(public_path('/assets/images/user_profiles/'), $image_name);
                $request->image->move(public_path('/assets/images/user_profiles/'), $filename);
            }
        
            if($request->file('image') != null){
                User::whereId(auth()->user()->id)->update([
                    'image' => '/assets/images/user_profiles/'.$filename,
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'sex' => $request->sex
        
                ]);
            }
            else{
                User::whereId(auth()->user()->id)->update([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'sex' => $request->sex
        
                ]);          
            }
            
            return Response()->json(array(
                'code' => 0,
                'msg' => "Success"
            ));
        }
        catch (\Exception $e) {
            return Response()->json(array(
                'code' => 1,
                'msg'=>$e->getMessage()
            ));
        }
    
       
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function Save(Request $request)
    {
        $code=0;

        if ($request->id == 0){
            
            $user_check = DB::select("SELECT COUNT(u.id) count_user FROM users u WHERE u.username = '$request->username'");
            if($user_check[0]->count_user > 0){
                $code=1;
            }
            else{
                $input['name'] = $request->name;
                $input['username'] = $request->username;
                $input['role_id'] = $request->role_id;
                $input['password'] = Hash::make($request->password);
                User::create($input);
            }
            
        }
        else{
            $user = User::find($request->id);
            $user_check = DB::select("SELECT COUNT(u.id) count_user FROM users u WHERE u.username = '$request->username' AND u.username != '$user->username'");
            if($user_check[0]->count_user > 0){
                $code=1;
            }
            else{
                $input = $request->all();
                $input = Arr::except($input,array('password'));
    
                
                $user->update($input);
            }
           
        }
        return Response()->json(array(
            'code' => $code,
        ));
    }

    public function Update(Request $request)
    {

        $user = User::find($request->id);

        if($user) {
            $user->active = $request->active;
            $user->save();
        }
        return Response()->json(array(
            'code' => 0,
        ));
    }

    public function ChangeLan(Request $request)
    {

        try{
            $lan =$request->lang;
            if($lan == "kh"){
                $lan ="en";
            }
            else{
                $lan ="kh";
            }
           
            App::setLocale($lan);
            session()->put('locale', $lan);  
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
    public function ResetPassword(Request $request)
    {

        $user = User::find($request->id);
        $default_password=$request->password;
        if($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }
        return Response()->json(array(
            'code' => 0,
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function  getData(){

        $data = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.name as role_name')
            ->get();
        return response()->json($data->toArray());

    }

    public function  getDistrict(Request $request ){

        // $od = DB::table('opdistrict')
        //     ->where('opdistrict.PRO_CODE',$request->pro_code)
        //     ->select('opdistrict.OD_CODE as id', 'opdistrict.OD_NAME_KH as text')
        //     ->get();
        $lang = app()->getLocale();
        $od=DB::select("SELECT o.OD_CODE as id, CASE WHEN '$lang'='kh' THEN o.OD_NAME_KH ELSE o.OD_NAME END text FROM opdistrict o WHERE o.PRO_CODE=$request->pro_code");
        return Response()->json(array(
            'district' => $od,

        ));
    }

    public function  GetODList(Request $request ){

        $lang = app()->getLocale();
        $od = DB::select("SELECT d.OD_CODE as id,CASE WHEN '$lang'='kh' THEN d.OD_NAME_KH ELSE d.OD_NAME END text FROM opdistrict d 
                        WHERE d.PRO_CODE = $request->pro_code
                            AND (d.OD_CODE = $request->od_code OR $request->od_code = 0)");
        return Response()->json(array(
            'district' => $od,

        ));
    }

    public function  getHF(Request $request ){

        $lang = app()->getLocale();
        $hf = DB::table('healthfacility')
            ->where('healthfacility.OD_CODE',$request->district_code)
            ->select('healthfacility.HFAC_CODE as id',DB::raw("CASE WHEN '$lang'='kh' THEN CONCAT(healthfacility.HFAC_Label,'-',healthfacility.HFAC_NAMEKh) ELSE CONCAT(healthfacility.HFAC_Label,'-',healthfacility.HFAC_NAME) END text"))
            ->get();
        return Response()->json(array(
            'HF' => $hf,

        ));
    }

    public function  GetHFList(Request $request ){

        // $hf = DB::table('healthfacility')
        //     ->where('healthfacility.OD_CODE',$request->district_code)
        //     ->select('healthfacility.HFAC_CODE as id',DB::raw("CONCAT(healthfacility.HFAC_Label,'-',healthfacility.HFAC_NAMEKh) AS text"))
        //     ->get();
        $lang = app()->getLocale();
        $hf = DB::select("SELECT h.HFAC_CODE as id,CASE WHEN '$lang'='kh' THEN CONCAT(h.HFAC_Label,'-',h.HFAC_NAMEKh) ELSE CONCAT(h.HFAC_Label,'-',h.HFAC_NAME) END text FROM healthfacility h 
                            WHERE h.OD_CODE = $request->district_code
                            AND (h.HFAC_CODE = $request->hf_code OR $request->hf_code = 0)");
        return Response()->json(array(
            'HF' => $hf,

        ));
    }

    public function  getInitPage(){
        $roleList = DB::table('roles')
            ->select('roles.id as id', 'roles.name as text')
            ->orderBy('roles.id')
            ->get();
     
        $username = DB::table('users')
            ->select('users.username')
            ->get();


        return Response()->json(array(
            'role' => $roleList,
            'username' => $username,

        ));
    }

}
