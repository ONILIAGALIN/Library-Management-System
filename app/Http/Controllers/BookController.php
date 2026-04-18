<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;
class BookController extends Controller
{
    public function index(){
        $books = Book::with('author', 'publisher', 'categories')->get();
        return response()->json([
            "ok" => true,
            "mesasage" => "Books retrieved successfully",
            "data" => $books
        ], 200);
    }

    public function show ($id){
        $book = Book::with('author', 'publisher', 'categories')->find($id);
        if(!$book){
            return response()->json([
                "ok" => false,
                "message" => "Book not found"
            ], 404);
        }
        return response()->json([
            "ok" => true,
            "message" => "Book retrieved successfully",
            "data" => $book
        ], 200);
    }

    public function store (Request $request){
        $validator = Validator::make($request->all(), [
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:books,isbn',
            'published_date' => 'required|date',
            'total_copies' => 'required|integer|min:0',
            'available_copies' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "message" => "Validation failed",
                "errors" => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $book = Book::create([
            'author_id' => $validated['author_id'],
            'publisher_id' => $validated['publisher_id'],
            'title' => $validated['title'],
            'isbn' => $validated['isbn'] ?? null,
            'published_date' => $validated['published_date'],
            'total_copies' => $validated['total_copies'],
            'available_copies' => $validated['available_copies'],
        ]);
        $book->categories()->sync($validated['category_ids']);

        return response()->json([
            "ok" => true,
            "message" => "Book created successfully",
            "data" => $book->load('categories')
        ], 201);
    }

    public function update (Request $request, $id){
        $book = Book::find($id);
        if(!$book){
            return response()->json([
                "ok" => false,
                "message" => "Book not found"
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'category_ids' => 'sometimes|nullble|array',
            'category_ids.*' => 'exists:categories,id',
            'author_id' => 'sometimes|nullable|exists:authors,id',
            'publisher_id' => 'sometimes|nullable|exists:publishers,id',
            'title' => 'sometimes|nullable|string|max:255',
            'isbn' => 'sometimes|nullable|string|max:20|unique:books,isbn,' . $id,
            'published_date' => 'sometimes|nullable|date',
            'total_copies' => 'sometimes|nullable|integer|min:0',
            'available_copies' => 'sometimes|nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "message" => "Validation failed",
                "errors" => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $book->update($validated);
        return response()->json([
            "ok" => true,
            "message" => "Book updated successfully",
            "data" => $book
        ], 200);
    }

    public function destroy ($id){
        $book = Book::find($id);
        if(!$book){
            return response()->json([
                "ok" => false,
                "message" => "Book not found"
            ], 404);
        }
        $book->delete();
        return response()->json([
            "ok" => true,
            "message" => "Book deleted successfully"
        ], 200);
    }
}
