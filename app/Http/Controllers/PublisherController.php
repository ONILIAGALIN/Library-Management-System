<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publisher;
use Illuminate\Support\Facades\Validator;
class PublisherController extends Controller
{
    public function index(){
        return response()->json([
            "ok" => true,
            "message" => "Publishers retrieved successfully",
            "data" => Publisher::paginate(10)
        ], 200);
    }

    public function show ($id){
        $publisher = Publisher::find($id);
        if(!$publisher){
            return response()->json([
                "ok" => false,
                "message" => "Publisher not found"
            ], 404);
        }
        return response()->json([
            "ok" => true,
            "message" => "Publisher retrieved successfully",
            "data" => $publisher
        ], 200);
    }

    public function store (Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:publishers,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "message" => "Validation failed",
                "errors" => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $publisher = Publisher::create($validated);
        return response()->json([
            "ok" => true,
            "message" => "Publisher created successfully",
            "data" => $publisher
        ], 201);
    }

    public function update (Request $request, $id){
        $publisher = Publisher::find($id);
        if(!$publisher){
            return response()->json([
                "ok" => false,
                "message" => "Publisher not found"
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|nullable|string|max:255|unique:publishers,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "message" => "Validation failed",
                "errors" => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $publisher->update($validated);
        return response()->json([
            "ok" => true,
            "message" => "Publisher updated successfully",
            "data" => $publisher
        ], 200);
    }

    public function destroy ($id){
        $publisher = Publisher::find($id);
        if(!$publisher){
            return response()->json([
                "ok" => false,
                "message" => "Publisher not found"
            ], 404);
        }
        $publisher->delete();
        return response()->json([
            "ok" => true,
            "message" => "Publisher deleted successfully"
        ], 200);
    }
}
