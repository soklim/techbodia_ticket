<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class UserAuthController extends Controller
{
    //
    public function login(Request $request){
     
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('username', 'password');
        if(auth()->attempt($credentials)){
            $token = auth()->user()->createToken('authToken')->accessToken;
            return response()->json([
                'status' => 'success',
                'data' => $token
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'data' => 'invalid credentials'
            ], 401);
        }
    }
}
