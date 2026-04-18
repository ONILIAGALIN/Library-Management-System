<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use Illuminate\Support\Facades\Validator;
class AuthorController extends Controller
{
    public function index(){
        $authors = Author::all();
        return response()->json([
            "ok" => true,
            "message" => "Authors retrieved successfully",
            "data" => $authors
        ], 200);
    }

    public function show ($id){
        $author = Author::find($id);
        if(!$author){
            return response()->json([
                "ok" => false,
                "message" => "Author not found"
            ], 404);
        }
        return response()->json([
            "ok" => true,
            "message" => "Author retrieved successfully",
            "data" => $author
        ], 200);
    }

    public function store (Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:authors,name',
            'pen_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "message" => "Validation failed",
                "errors" => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $author = Author::create([
            'name' => $validated['name'],
            'pen_name' => $validated['pen_name'] ?? null,
        ]);
        return response()->json([
            "ok" => true,
            "message" => "Author created successfully",
            "data" => $author
        ], 201);
    }

    public function update (Request $request, $id){
        $author = Author::find($id);
        if(!$author){
            return response()->json([
                "ok" => false,
                "message" => "Author not found"
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|nullable|string|max:255|unique:authors,name,' . $author->id,
            'pen_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "message" => "Validation failed",
                "errors" => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $author->update([
            'name' => $validated['name'] ?? $author->name,
            'pen_name' => $validated['pen_name'] ?? $author->pen_name,
        ]);
        return response()->json([
            "ok" => true,
            "message" => "Author updated successfully",
            "data" => $author
        ], 200);
    }

    public function destroy ($id){
        $author = Author::find($id);
        if(!$author){
            return response()->json([
                "ok" => false,
                "message" => "Author not found"
            ], 404);
        }
        $author->delete();
        return response()->json([
            "ok" => true,
            "message" => "Author deleted successfully"
        ], 200);
    }
}
