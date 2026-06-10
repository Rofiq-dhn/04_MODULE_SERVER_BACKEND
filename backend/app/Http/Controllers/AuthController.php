<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{

    public function Register(Request $request) {
        $$request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:id',
            'password'=> 'required|min:6',
        ]);

        $user = $request->user();

         $token = $user->createToke('auth-token')->plainTextContent;

        User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'password'=> bcrypt($request->password),
        ]);

    
        return response()->json([
            'status'=> 'success',
            'message'=> 'Registration successful',
            'data' => [
                'id' => $user->id,
                'name'=> $user->name,
                'email'=> $user->email,
                'created_at' => $user->created_at,
                'updated_at'=> $user->updated_at,
                'token' => $token
            ]
        ]);
    }
    public function login(Request $request){
        $data =  $request->validate([
            'email' => 'required',
            'password'=> 'required'
        ]);

        $user = User::where('email',$data['email'])->first();

        if(!$user || !$user->checkPassword($data['password'])){
         return response()->json([
            'status'=> 'error',
            'message'=> 'Username or password incorrect'
         ], 401);
    }

    $token = $user->createToke('auth-token')->plainTextContent;

    return response()->json([
        'status'=> 'success',
        'message'=> 'Login successful',
        'data' => [
             'id' => $user->id,
             'name' => $user->name,
             'email'=> $user->email,
             'created_at' => $user->created_at,
             'updated_at'=> $user->updated_at,
             'token'=> $token
        ]
    ]);
}

  public function logout(Request $request){
    $user = $request->user();
    $user->currentAccessToken()->delete();

    return response()->json([
      'status' => 'success',
      'message'=> 'Logout successful'
    ], );
  }
}
