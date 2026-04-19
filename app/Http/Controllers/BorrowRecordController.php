<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BorrowRecord;
use Illuminate\Support\Facades\Validator;
class BorrowRecordController extends Controller
{
    public function index(){
        return response()->json([
            "ok" => true,
            "message" => "Borrowed Records retrieved successfully",
            "data" => BorrowRecord::with('user', 'book')->get()
        ], 200);
    }

    public function show ($id){
        $borrow_record = BorrowRecord::with('user', 'book')->find($id);
        if(!$borrow_record){
            return response()->json([
                "ok" => false,
                "message" => "Borrow Record not found"
            ], 404);
        }
        return response()->json([
            "ok" => true,
            "message" => "Borrow Record retrieved successfully",
            "data" => $borrow_record
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
        $book_record = BorrowRecord::create([
            'user_id' => $validated ["user_id"],
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
        $borrow_record = BorrowRecord::find($id);
        if(!$borrow_record){
            return response()->json([
                "ok" => false,
                "message" => "Borrow Record not found"
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
        $borrow_record->update([
            'user_id' => auth()->id(),
            'book_id' => $validated['book_id'] ?? $borrow_record->book_id,
            'borrow_date' => $validated['borrow_date'] ?? $borrow_record->borrow_date,
            'return_date' => $validated['return_date'] ?? $borrow_record->return_date,
            'status' => $validated['status'] ?? $borrow_record->status,
        ]);
        return response()->json([
            "ok" => true,
            "message" => "Book Record updated successfully",
            "data" => $borrow_record
        ], 200);
    }

    public function destroy ($id){
        $borrow_record = BorrowRecord::find($id);
        if(!$borrow_record){
            return response()->json([
                "ok" => false,
                "message" => "Borrow Record not found"
            ], 404);
        }
        $borrow_record->delete();
        return response()->json([
            "ok" => true,
            "message" => "Borrow Record deleted successfully"
        ], 200);
    }
}
