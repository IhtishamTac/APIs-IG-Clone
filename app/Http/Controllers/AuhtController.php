<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuhtController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'full_name' => 'required',
            'bio' => 'required|max:100',
            'username' => 'required|min:3|unique:users,username|regex/^(?.*[A-Z])(?.&[._])[a-zA-Z0-9._]+$/',
            'password' => 'required|min:6',
            'is_private' => 'boolean'
        ],[
            'username.regex' => 'Username need to have atlest one Uppercase letter and ( . and - ) symbols and only that symbols allowed'
        ]);
        if($validator->fails()){
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 200);
        }

        $credentials = $request->only(['username', 'password']);
        if(!auth()->attempt($credentials)){
            return response()->json([
            ], 404);
        }

        $user = User::where('username', $request->username)->first();
        $token = $user->createToken('SacntumToken')->plainTextToken;
        return response()->json([
            'message' => 'Register success',
            'token' => $token,
            'user' => $user
        ], 200);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'username' => 'required',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 200);
        }
        $credentials = $request->only(['username', 'password']);
        if(!auth()->attempt($credentials)){
            return response()->json([
                'message' => 'Incorrect username or password'
            ], 404);
        }
        $user = User::where('username', $request->username)->first();
        $token = $user->createToken('SacntumToken')->plainTextToken;
        return response()->json([
            'message' => 'Login success',
            'token' => $token,
            'user' => $user
        ], 200);
    }

    public function logout(Request $request){
        if($request->user()->Tokens()->delete()){
            return response()->json([
                'message' => 'Logout success',
            ], 200);
        }
    }
}
