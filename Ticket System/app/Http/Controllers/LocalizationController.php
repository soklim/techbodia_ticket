<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocalizationController extends Controller
{
    //
    public function lang_change(Request $request)
    {
        try{
            App::setLocale($request->lang);
            session()->put('locale', $request->lang);  
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
