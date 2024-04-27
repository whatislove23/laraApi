<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email|unique:users,email",
            "name" => "required",
            "password" => "required",
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => "Validation error",
                "error" => $validator->errors()
            ], 401);
        }
        $user = User::create([
            "email" => $request->name,
            "password" => $request->password,
            "name" => $request->name,
        ]);

        return response()->json([
            "status" => true,
            "message" => "User has been created",
            "data" => ["token" => $user->createToken("api token")->plainTextToken],
        ], 200);
    }

    public function login(Request $request)
    {
        $user = User::where("email", $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'data' => 'user doesnt exist'
            ], 400);
        }
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'data' => 'Wrong password'
            ], 400);
        }
        return response()->json([
            "status" => true,
            "data" => ["token" => $user->createToken("api auth")->plainTextToken],
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            "status" => true,
            "data" => ["message" => "Logout succsesfully"]
        ]);
    }
}