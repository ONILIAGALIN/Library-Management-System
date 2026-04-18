<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
class UserController extends Controller
{
    public function index (){
        return response()->json([
            "ok" => true,
            "message" => "User successfully retrieved",
            "data" => User::with('profile')->get()
        ], 200);
    }

    public function show ($id){
        $user = User::with('profile')->find($id);
        if (!$user) {
            return response()->json([
                "ok" => false,
                "message" => "User not found",
            ], 404);
        }
        return response()->json([
            "ok" => true,
            "message" => "Specific User details successfully retrieved",
            "data" => $user
        ], 200);
    }

    public function store (Request $request, User $user){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:8|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'first_name' => 'required|string|min:2|max:32',
            'middle_name' => 'nullable|string|min:2|max:32',
            'last_name' => 'required|string|min:2|max:32',
            'phone_number' => ["required", "string", "min:11", "regex:/^(09|\+639)\d{9}$/", "not_regex:/[a-zA-Z]/"],
        ]);
        if($validator->fails()){
            return response()->json([
                "ok" => false,
                "message" => "Create User didn't pass validation",
                "errors" => $validator->errors()
            ], 422);
        }

        $user_input = $validator->safe()->only(['name', 'email', 'password', 'role_id']);
        $profile_input = $validator->safe()->except(['name', 'email', 'password', 'role_id']);

        $user = User::create($user_input);
        $user->profile()->create($profile_input);
        $user->profile;
        return response()->json([
            "ok" => true,
            "message" => "User registered successfully",
            "data" => $user
        ], 201);
    }

    public function update (Request $request, User $user){
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|nullable|string|min:8|max:100' . $user->id,
            'email' => 'sometimes|nullable|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|nullable|string|min:8|confirmed',
            'role_id' => 'sometimes|nullable|in:Admin,User',
            'first_name' => 'sometimes|nullable|string|min:2|max:32',
            'middle_name' => 'nullable|string|min:2|max:32',
            'last_name' => 'sometimes|nullable|string|min:2|max:32',
            'phone_number' => ["sometimes", "string", "min:11", "regex:/^(09|\+639)\d{9}$/", "not_regex:/[a-zA-Z]/"],
        ]);
        if($validator->fails()){
            return response()->json([
                "ok" => false,
                "message" => "Update User didn't pass validation",
                "errors" => $validator->errors()
            ], 422);
        }

        $user_input = $validator->safe()->only(['name','email','password','role_id']);
        $profile_input = $validator->safe()->except(['name','email','password','role_id']);
        $user->update($user_input);
        $user->profile()->update($profile_input);
        $user->profile;

        return response()->json([
            "ok" => true,
            "message" => "User updated successfully",
            "data" => $user
        ], 200);
    }

    public function destroy (User $user){
        $user->delete();
        return response()->json([
            "ok" => true,
            "message" => "User deleted successfully",
        ], 200);
    }
}