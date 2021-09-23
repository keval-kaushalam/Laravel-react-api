<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthApiController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'firstname'=>'required|max:191',
            'lastname'=>'required|max:191',
            'email'=>'required|email|max:191|unique:users,email',
            'password'=>'required|min:8'
        ]);

        if($validator->fails()){
            return response()->json([
                'validation_error'=>$validator->messages()
            ]);
        }else{
            $user = User::create([
                'firstname'=>$request->firstname,
                'lastname'=>$request->lastname,
                'email'=>$request->email,
                'password'=>Hash::make($request->password)
            ]);

            $token = $user->createToken($user->email.'_Token')->plainTextToken;

            $user['token'] = $token;
            $user['status']=200;

            return response()->json($user);
        }
    }
}
