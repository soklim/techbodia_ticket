<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserAuthController extends Controller
{
    
    /// register user with passport 

    /// login 
    public function login(Request $request){
        // dd($request);
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        $credentials = $request->only('username','password');
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
