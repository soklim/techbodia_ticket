<?php

namespace App\Http\Controllers;

use App\Models\API;
use App\Models\HealthFacility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Hash;

class APIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rolde_id = Auth::user()->role_id;
        $module_id = 14;
        $permission = DB::table('module_permissions')->where('role_id', $rolde_id)->where('module_id', $module_id)->first();
        if ($permission->a_read != 1){
            return view('error.error404');
        }
        else{
            $api_url = DB::table("api_url as u")->select("u.*")->get();
            return view('api.index',['api_url' => $api_url]);
        }
    }

    public function Save(Request $request)
    {
        if ($request->id == 0){
            $input['api_id'] = $request->api_id;
            $input['username'] = $request->username ;
            $input['api_key'] = $request->api_key ;
            API::create($input);
        }
        else{
            $input = $request->all();
            $data = API::find($request->id);
            $data->update($input);
        }
        return Response()->json(array(
            'code' => 0,
        ));
    }
    public function  getData(){

        $data = DB::table('api_users as a')
            ->join('api_url as u', 'a.api_id', '=', 'u.id')
            ->select('a.*', 'u.*')
            ->orderByRaw('a.id ASC')
            ->get();
        return response()->json($data->toArray());

    }

    public function Death_Notification(Request $request){

        if($request->url_id == null || $request->death_id == null || $request->api_key == null || $request->url_id == "" || $request->death_id == "" || $request->api_key == ""){
            return response()->json(['error'=>'Invalid parameter']);
        }
        else{
            $count_api = DB::table('api_users')->where("id",$request->url_id)->where("api_key",$request->api_key)->count();

            if ($count_api == 0){
                return response()->json(['error'=>'Invalid API Key']);
            }
            else{
                $data = DB::table("emr_death as d")
                    ->leftJoin("healthfacility as h", function($join){
                        $join->on("d.hmis_code", "=", "h.hfac_code");
                    })
                    ->leftJoin("province as p1", function($join){
                        $join->on("d.deceased_province_code", "=", "p1.procode");
                    })
                    ->leftJoin("district as dt1", function($join){
                        $join->on("d.deceased_district_code", "=", "dt1.dcode");
                    })
                    ->leftJoin("commune as c1", function($join){
                        $join->on("d.deceased_commune_code", "=", "c1.ccode");
                    })
                    ->leftJoin("village as v", function($join){
                        $join->on("d.deceased_village", "=", "v.vcode");
                    })
                    ->leftJoin("setting_items as s1", function($join){
                        $join->on("d.death_info", "=", "s1.item_id")
                            ->where("s1.type_id", "=", 2);
                    })
                    ->leftJoin("setting_items as s2", function($join){
                        $join->on("d.death_type", "=", "s2.item_id")
                            ->where("s2.type_id", "=", 3);
                    })
                    ->leftJoin("setting_items as s3", function($join){
                        $join->on("d.sex", "=", "s3.item_id")
                            ->where("s3.type_id", "=", 1);
                    })
                    ->leftJoin("setting_items as s4", function($join){
                        $join->on("d.married_status", "=", "s4.item_id")
                            ->where("s4.type_id", "=", 4);
                    })
                    ->leftJoin("healthfacility as hf", function($join){
                        $join->on("d.hmis_code", "=", "hf.HFAC_CODE");
                    })
                    ->where("d.death_id", $request->death_id)
                    ->select("d.death_id","d.issue_no", "h.hfac_namekh as hfac_label", "s1.name_kh as death_info",
                        "s2.name_kh as death_type", "s3.name_kh as sex", "s4.name_kh as married_status","d.deceased_name",
                        "d.medical_file_id", "d.date_of_death", "d.time_of_death",
                        "p1.province_kh as deceased_province_code","dt1.DName_kh as deceased_district_code",
                        "c1.CName_kh as deceased_commune_code", "v.VName_kh as deceased_village",
                        "d.deceased_street", "d.deceased_house",
                    )
                    ->get();
                return response()->json($data->toArray());
            }
        }

    }

    public function Add_HF(Request $request)
    {
        if($request->api_key == ""){
            return response()->json(['error'=>'Invalid parameter']);
        }
        else{
            $count_api = DB::table('api_users')->where("api_key",$request->api_key)->count();

            if ($count_api == 0){
                return response()->json(['error'=>'Invalid API Key']);
            }
            else{

                $input['HF_Group'] = $request->HF_Group;
                $input['HFAC_Label'] = $request->HFAC_Label ;
                $input['HFAC_NAME'] = $request->HFAC_NAME;
                $input['HFAC_NAMEKh'] = $request->HFAC_NAMEKh; 
                $input['hasANC'] = $request->hasANC; 
                $input['hasMAT'] = $request->hasMAT; 

                            
                $data = HealthFacility::create($input);
                return Response()->json(array(
                    'code' => 0,'msg'=>'Success'
                ));
            }
        }
        
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function API_Submit(Request $request)
    {


        $fail = DB::select("SELECT a.api_type_id,a.data_id FROM api_fail a WHERE a.id = $request->fail_id");
        $id = $fail[0]->data_id;
        $type_id = $fail[0].api_type_id;
        if($type_id == 1){
            
            $emr = DB::select("SELECT 
            e.issue_no as notification_token
            ,h.HFAC_NAMEKh as station_name
            ,CONCAT(p.PROVINCE_KH,'-',od.OD_NAME_KH) as station_address
            ,LPAD(IFNULL(e.occurrence_province_code,'0'), 2, 0) as occurrence_province_code
            ,LPAD(IFNULL(e.occurrence_district_code,'0'), 4, 0) as occurrence_district_code
            ,LPAD(IFNULL(e.occurrence_commune_code,'0'), 6, 0) as occurrence_commune_code
            ,IFNULL(p2.PROVINCE_KH,'N/A') as occurrence_province
            ,IFNULL(d2.DName_kh,'N/A') as occurrence_district
            ,IFNULL(c2.CName_kh,'N/A') as occurrence_commune  
            ,IFNULL(DATE_FORMAT(e.date_of_death, '%d-%m-%Y'),'00-00-0000') as death_date
            ,IFNULL(LEFT(e.time_of_death,5),'00:00') as death_time
            ,IFNULL(e.deceased_name,'N/A') as givenname_kh
            ,IFNULL(e.deceased_sure_name,'N/A') as surname_kh
            ,IFNULL(DATE_FORMAT(e.date_of_birth, '%d-%m-%Y'),'00-00-0000') as dob
            ,CASE WHEN e.sex = 1 THEN 'M' WHEN e.sex =2 THEN 'F' ELSE 'O' END gender
            ,LPAD(IFNULL(e.deceased_province_code,'0'), 2, 0) as pol_province_code
            ,LPAD(IFNULL(e.deceased_district_code,'0'), 4, 0) as pol_district_code
            ,LPAD(IFNULL(e.deceased_commune_code,'0'), 6, 0) as pol_commune_code
            ,LPAD(IFNULL(e.deceased_village,'0'), 8, 0) as pol_village_code 
            ,IFNULL(p1.PROVINCE_KH,'N/A') as pol_province
            ,IFNULL(d1.DName_kh,'N/A') as pol_district
            ,IFNULL(c1.CName_kh,'N/A') as pol_commune
            ,IFNULL(v1.VName_kh,'N/A') as pol_village
            ,s3.name_kh as death_manner
            ,IFNULL(e.contact_phone,'000-000000') as contact
            ,s2.name_kh as type_place_occurrence
            FROM emr_death e 
            INNER JOIN healthfacility h ON e.hmis_code = h.HFAC_CODE
            INNER JOIN opdistrict od ON od.OD_CODE = h.OD_CODE
            INNER JOIN province p ON od.PRO_CODE =p.PROCODE
            LEFT JOIN province p2 ON e.occurrence_province_code = p2.PROCODE
            LEFT JOIN district d2 ON e.occurrence_district_code = d2.DCode
            LEFT JOIN commune c2 ON e.occurrence_commune_code = c2.CCode
            LEFT JOIN province p1 ON e.deceased_province_code = p1.PROCODE
            LEFT JOIN district d1 ON e.deceased_district_code = d1.DCode
            LEFT JOIN commune c1 ON e.deceased_commune_code = c1.CCode
            LEFT JOIN village v1 ON e.deceased_village = v1.VCode
            INNER JOIN setting_items s3 ON e.death_type = s3.item_id AND s3.type_id = 3
            INNER JOIN setting_items s2 ON e.death_info = s2.item_id AND s2.type_id =2
            WHERE e.death_id= $id LIMIT 1");
        
            $token = env('API_TOKEN');
    
            $response = Http::withHeaders(['Authorization'=> 'Bearer '.$token,])->post('https://nhf.gdi.gov.kh/api/death/v1/notification', [
                'notification_token' => $emr[0]->notification_token,
                'station_name' => $emr[0]->station_name,
                'station_address' => $emr[0]->station_address,
                'type_place_occurrence' => $emr[0]->type_place_occurrence,
                'occurrence_province' => $emr[0]->occurrence_province,
                'occurrence_district' => $emr[0]->occurrence_district,
                'occurrence_commune' => $emr[0]->occurrence_commune,
                'occurrence_province_code' => $emr[0]->occurrence_province_code,
                'occurrence_district_code' => $emr[0]->occurrence_district_code,
                'occurrence_commune_code' => $emr[0]->occurrence_commune_code,
                'death_date' => $emr[0]->death_date,
                'death_time' => $emr[0]->death_time,
                'surname_kh' => $emr[0]->surname_kh,
                'givenname_kh' => $emr[0]->givenname_kh,              
                'dob' => $emr[0]->dob,
                'gender' => $emr[0]->gender,
                'pol_province_code' => $emr[0]->pol_province_code,
                'pol_district_code' => $emr[0]->pol_district_code,
                'pol_commune_code' => $emr[0]->pol_commune_code,
                'pol_village_code' => $emr[0]->pol_village_code,
                'pol_province' => $emr[0]->pol_province,
                'pol_district' => $emr[0]->pol_district,
                'pol_commune' => $emr[0]->pol_commune,
                'pol_village' => $emr[0]->pol_village,              
                'death_manner' => $emr[0]->death_manner,
                'contact' => $emr[0]->contact
    
            ]);
        
            $jsonData = $response->json();
    
            if($jsonData["code"] == 300){
                $new_token = Http::get('http://example.com');
    
                $path = base_path('.env');
                $test = file_get_contents($path);
                
                if (file_exists($path)) {
                    file_put_contents($path, str_replace('API_TOKEN='.$token, 'API_TOKEN='.$new_token["access_token"], $test));
                }
    
                $response = Http::withHeaders(['Authorization'=> 'Bearer '.$token,])->post('https://nhf.gdi.gov.kh/api/death/v1/notification', [
                    'notification_token' => $emr[0]->notification_token,
                    'station_name' => $emr[0]->station_name,
                    'station_address' => $emr[0]->station_address,
                    'type_place_occurrence' => $emr[0]->type_place_occurrence,
                    'occurrence_province' => $emr[0]->occurrence_province,
                    'occurrence_district' => $emr[0]->occurrence_district,
                    'occurrence_commune' => $emr[0]->occurrence_commune,
                    'occurrence_province_code' => $emr[0]->occurrence_province_code,
                    'occurrence_district_code' => $emr[0]->occurrence_district_code,
                    'occurrence_commune_code' => $emr[0]->occurrence_commune_code,
                    'death_date' => $emr[0]->death_date,
                    'death_time' => $emr[0]->death_time,
                    'surname_kh' => $emr[0]->surname_kh,
                    'givenname_kh' => $emr[0]->givenname_kh,              
                    'dob' => $emr[0]->dob,
                    'gender' => $emr[0]->gender,
                    'pol_province_code' => $emr[0]->pol_province_code,
                    'pol_district_code' => $emr[0]->pol_district_code,
                    'pol_commune_code' => $emr[0]->pol_commune_code,
                    'pol_village_code' => $emr[0]->pol_village_code,
                    'pol_province' => $emr[0]->pol_province,
                    'pol_district' => $emr[0]->pol_district,
                    'pol_commune' => $emr[0]->pol_commune,
                    'pol_village' => $emr[0]->pol_village,              
                    'death_manner' => $emr[0]->death_manner,
                    'contact' => $emr[0]->contact
    
                ]);
    
                $jsonData = $response->json();
    
            }
    
            if($jsonData["code"] != 200){
                
                $msg ="Fail API";
                DB::insert('INSERT INTO api_fail(api_type_id,data_id,created_by,message_fail,data_fail) 
                            VALUES(1,?,?,?)',[$$data->death_id,$userId,$jsonData["message"],$jsonData["data"]]);
            }
        }
        else if($type_id == 2){

            $emr = DB::select("SELECT e.birth_no as notification_token
                ,h.HFAC_NAMEKh as station_name
                ,CONCAT(od.OD_NAME_KH,'-',p.PROVINCE_KH) as station_address       
                ,IFNULL(e.mother_sure_name,'គ្មាន') as mother_surname_kh
                ,IFNULL(mothername,'គ្មាន') as mother_givenname_kh
                ,IFNULL(DATE_FORMAT(e.motherdofbirth, '%d-%m-%Y'),'01-01-1900') as mother_dob
                ,LPAD(IFNULL(e.mPCode,'0'), 2, 0) as mother_pol_province_code
                ,LPAD(IFNULL(e.mDCode,'0'), 4, 0) as mother_pol_district_code
                ,LPAD(IFNULL(e.mCCode,'0'), 6, 0) as mother_pol_commune_code
                ,LPAD(IFNULL(e.mVCode,'0'), 8, 0) as mother_pol_village_code
                ,IFNULL(p1.PROVINCE_KH,'N/A') as mother_pol_province
                ,IFNULL(d1.DName_kh,'N/A') as mother_pol_district
                ,IFNULL(c1.CName_kh,'N/A') as mother_pol_commune
                ,IFNULL(v1.VName_kh,'N/A') as mother_pol_village
                ,IFNULL(e.father_sure_name,'គ្មាន') as father_surname_kh
                ,IFNULL(fathername,'គ្មាន') as father_givenname_kh
                ,IFNULL(DATE_FORMAT(e.fatherdofbirth, '%d-%m-%Y'),'00-00-0000') as father_dob
                ,IFNULL(e.baby_first_name,'គ្មាន') as surname_kh
                ,IFNULL(e.baby_first_name,'គ្មាន') as givenname_kh
                ,IFNULL(DATE_FORMAT(e.dateofbirth, '%d-%m-%Y'),'00-00-0000') as dob
                ,CASE WHEN e.sex = 1 THEN 'M' WHEN e.sex =2 THEN 'F' ELSE 'O' END gender
                ,IFNULL(LEFT(e.time_of_birth,5),'00:00') as birth_time
                ,IFNULL(e.baby_weight,'N/A') as weight
                ,IFNULL(s6.name_kh,'N/A') as type_place_occurrence
                ,LPAD(IFNULL(e.occurrence_province_code,'0'), 2, 0) as occurrence_province_code
                ,LPAD(IFNULL(e.occurrence_district_code,'0'), 4, 0) as occurrence_district_code            
                ,LPAD(IFNULL(e.occurrence_commune_code,'0'), 6, 0) as occurrence_commune_code            
                ,IFNULL(p2.PROVINCE_KH,'N/A') as occurrence_province
                ,IFNULL(d2.DName_kh,'N/A') as occurrence_district
                ,IFNULL(c2.CName_kh,'N/A') as occurrence_commune
                ,s7.name_kh as birth_type
                ,s8.name_kh as attendant_at_delivery
                ,IFNULL(e.numofchildalive,'0') as child_alive
                ,IFNULL(e.contact_phone,'000-000000') as contact
                FROM emr_birth e 
                INNER JOIN healthfacility h ON e.hfac_code = h.HFAC_CODE
                INNER JOIN opdistrict od ON od.OD_CODE = h.OD_CODE
                INNER JOIN province p ON od.PRO_CODE =p.PROCODE
                LEFT JOIN province p1 ON e.mPCode = p1.PROCODE
                LEFT JOIN district d1 ON e.mDCode = d1.DCode
                LEFT JOIN commune c1 ON e.mCCode = c1.CCode
                LEFT JOIN village v1 ON e.mVCode = v1.VCode
                LEFT JOIN setting_items s6 ON e.birth_info = s6.item_id AND s6.type_id = 6
                LEFT JOIN setting_items s7 ON e.typeofbirth = s7.item_id AND s7.type_id = 7
                LEFT JOIN setting_items s8 ON e.Atdelivery = s8.item_id AND s8.type_id = 8
                LEFT JOIN province p2 ON p2.PROCODE = e.occurrence_province_code
                LEFT JOIN district d2 ON d2.DCode = e.occurrence_district_code
                LEFT JOIN commune c2 ON c2.CCode = e.occurrence_commune_code
                WHERE e.bid = $id LIMIT 1");
    
                $token = env('API_TOKEN');
                $response = Http::withHeaders(['Authorization'=> 'Bearer '.$token,])->post('https://nhf.gdi.gov.kh/api/birth/v1/notification', [
                    'notification_token' => $emr[0]->notification_token,
                    "station_name"=> $emr[0]->station_name,
                    "station_address"=> $emr[0]->station_address,
                    "mother_surname_kh"=> $emr[0]->mother_surname_kh,
                    "mother_givenname_kh"=> $emr[0]->mother_givenname_kh,
                    "mother_dob"=> $emr[0]->mother_dob,
                    "mother_pol_province_code"=> $emr[0]->mother_pol_province_code,
                    "mother_pol_district_code"=> $emr[0]->mother_pol_district_code,
                    "mother_pol_commune_code"=> $emr[0]->mother_pol_commune_code,
                    "mother_pol_province"=> $emr[0]->mother_pol_province,
                    "mother_pol_district"=> $emr[0]->mother_pol_district,
                    "mother_pol_commune"=> $emr[0]->mother_pol_commune,
                    "father_surname_kh"=> $emr[0]->father_surname_kh,
                    "father_givenname_kh"=> $emr[0]->father_givenname_kh,
                    "father_dob"=> $emr[0]->father_dob,
                    "father_age"=> "33",
                    "surname_kh"=> $emr[0]->surname_kh,
                    "givenname_kh"=> $emr[0]->givenname_kh,
                    "dob"=> $emr[0]->dob,
                    "birth_time"=> $emr[0]->birth_time,
                    "gender"=> $emr[0]->gender,
                    "weight"=> $emr[0]->weight,
                    "type_place_occurrence"=> $emr[0]->type_place_occurrence,
                    "occurrence_province"=> $emr[0]->occurrence_province,
                    "occurrence_district"=> $emr[0]->occurrence_district,
                    "occurrence_commune"=> $emr[0]->occurrence_commune,
                    "occurrence_province_code"=> $emr[0]->occurrence_province_code,
                    "occurrence_district_code"=> $emr[0]->occurrence_district_code,
                    "occurrence_commune_code"=> $emr[0]->occurrence_commune_code,
                    "birth_type"=> $emr[0]->birth_type,
                    "attendant_at_delivery"=> $emr[0]->attendant_at_delivery,
                    "child_alive"=> $emr[0]->child_alive,
                    "contact"=>$emr[0]->contact,
    
                ]);
    
                $jsonData = $response->json();
                if($jsonData["code"] != 200){
                    $msg ="Fail API";
                    DB::insert('INSERT INTO api_fail(api_type_id,data_id,created_by,message_fail,data_fail) 
                                VALUES(2,?,?,?)',[$$data->death_id,$userId,$jsonData["message"],$jsonData["data"]]);
                }
        }
      
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
     * @param  \App\Models\API  $aPI
     * @return \Illuminate\Http\Response
     */
    public function show(API $aPI)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\API  $aPI
     * @return \Illuminate\Http\Response
     */
    public function edit(API $aPI)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\API  $aPI
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, API $aPI)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\API  $aPI
     * @return \Illuminate\Http\Response
     */
    public function destroy(API $aPI)
    {
        //
    }
}
