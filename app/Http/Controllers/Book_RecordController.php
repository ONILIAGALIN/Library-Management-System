<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book_Record;
use Illuminate\Support\Facades\Validator;
class Book_RecordController extends Controller
{
    public function index(){
        return response()->json([
            "ok" => true,
            "message" => "Book Records retrieved successfully",
            "data" => Book_Record::with('user', 'book')->get()->paginate(10)
        ], 200);
    }

    public function show ($id){
        $book_record = Book_Record::with('user', 'book')->findOrFail($id);
        if(!$book_record){
            return response()->json([
                "ok" => false,
                "message" => "Book Record not found"
            ], 404);
        }
        return response()->json([
            "ok" => true,
            "message" => "Book Record retrieved successfully",
            "data" => $book_record
        ], 200);
    }

    public function store (Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'status' => 'required|string|in:borrowed,returned,overdue',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "message" => "Validation failed",
                "errors" => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $book_record = Book_Record::create([
            'user_id' => auth()->id(),
            'book_id' => $validated['book_id'],
            'borrow_date' => $validated['borrow_date'],
            'return_date' => $validated['return_date'],
            'status' => $validated['status'],
        ]);
        return response()->json([
            "ok" => true,
            "message" => "Book Record created successfully",
            "data" => $book_record
        ], 201);
    }

    public function update (Request $request, $id){
        $book_record = Book_Record::find($id);
        if(!$book_record){
            return response()->json([
                "ok" => false,
                "message" => "Book Record not found"
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|nullable|exists:users,id',
            'book_id' => 'sometimes|nullable|exists:books,id',
            'borrow_date' => 'sometimes|nullable|date',
            'return_date' => 'sometimes|nullable|date|after_or_equal:borrow_date',
            'status' => 'sometimes|nullable|string|in:borrowed,returned,overdue',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "message" => "Validation failed",
                "errors" => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $book_record->update([
            'user_id' => auth()->id(),
            'book_id' => $validated['book_id'] ?? $book_record->book_id,
            'borrow_date' => $validated['borrow_date'] ?? $book_record->borrow_date,
            'return_date' => $validated['return_date'] ?? $book_record->return_date,
            'status' => $validated['status'] ?? $book_record->status,
        ]);
        return response()->json([
            "ok" => true,
            "message" => "Book Record updated successfully",
            "data" => $book_record
        ], 200);
    }

    public function destroy ($id){
        $book_record = Book_Record::find($id);
        if(!$book_record){
            return response()->json([
                "ok" => false,
                "message" => "Book Record not found"
            ], 404);
        }
        $book_record->delete();
        return response()->json([
            "ok" => true,
            "message" => "Book Record deleted successfully"
        ], 200);
    }
}
