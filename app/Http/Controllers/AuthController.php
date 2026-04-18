<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
    public function register (Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:8|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'first_name' => 'required|string|min:2|max:32',
            'middle_name' => 'nullable|string|min:2|max:32',
            'last_name' => 'required|string|min:2|max:32',
            'phone_number' => 'required|string|min:7|max:15',
        ]);
        if($validator->fails()){
            return response()->json([
                "ok" => false,
                "message" => "MALI PO  ANG NILAGAY NYONG DATA O KULANG",
                "errors" => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $user_input = $validator->safe()->only(['name', 'email', 'password', 'role_id']);
        $profile_input = $validator->safe()->except(['name', 'email', 'password', 'role_id']);

        $user = User::create($user_input);
        $user->profile()->create($profile_input);
        $user->profile;
        $user->token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            "ok" => true,
            "message" => "User registered successfully",
            "data" => $user
        ], 201);
    }

    public function login (Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if($validator->fails()){
            return response()->json([
                "ok" => false,
                "message" => "Login didn't pass validation",
                "errors" => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $user = User::where('email', $validated['email'])->first();
        if (!$user || !\Hash::check($validated['password'], $user->password)) {
            return response()->json([
                "ok" => false,
                "message" => "Invalid email or password",
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            "ok" => true,
            "message" => "User logged in successfully",
            "data" => [
                "user" => $user,
                "token" => $token
            ]
        ], 200);
    }
}
