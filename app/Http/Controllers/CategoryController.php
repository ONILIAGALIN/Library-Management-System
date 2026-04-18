<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
class CategoryController extends Controller
{
    function index(){
        $categories = Category::all();
        return response()->json([
            "ok" => true,
            "message" => "Categories retrieved successfully",
            "data" => $categories
        ], 200);
    }

    public function show ($id){
        $category = Category::find($id);
        if(!$category){
            return response()->json([
                "ok" => false,
                "message" => "Category not found"
            ], 404);
        }
        return response()->json([
            "ok" => true,
            "message" => "Category retrieved successfully",
            "data" => $category
        ], 200);
    }

    public function store (Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "message" => "Validation failed",
                "errors" => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $category = Category::create($validated);
        return response()->json([
            "ok" => true,
            "message" => "Category created successfully",
            "data" => $category
        ], 201);
    }

    public function update (Request $request, $id){
        $category = Category::find($id);
        if(!$category){
            return response()->json([
                "ok" => false,
                "message" => "Category not found"
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|nullable|string|max:255|unique:categories,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "message" => "Validation failed",
                "errors" => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $category->update($validated);
        return response()->json([
            "ok" => true,
            "message" => "Category updated successfully",
            "data" => $category
        ], 200);
    }

    public function destroy ($id){
        $category = Category::find($id);
        if(!$category){
            return response()->json([
                "ok" => false,
                "message" => "Category not found"
            ], 404);
        }
        $category->delete();
        return response()->json([
            "ok" => true,
            "message" => "Category deleted successfully"
        ], 200);
    }
}
