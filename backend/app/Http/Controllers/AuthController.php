<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
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
        ]
    ]);
}

  public function logout(){

  }
}
