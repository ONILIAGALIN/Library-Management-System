<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fine;
use Illuminate\Support\Facades\Validator;
class FineController extends Controller
{
    public function index(){
        return response()->json([
            "ok" => true,
            "message" => "Fines retrieved successfully",
            "data" => Fine::with('borrowRecord')->paginate(10)
        ], 200);
    }

    public function show ($id){
        $fine = Fine::with('borrowRecord')->findOrFail($id);
        if(!$fine){
            return response()->json([
                "ok" => false,
                "message" => "Fine not found"
            ], 404);
        }
        return response()->json([
            "ok" => true,
            "message" => "Fine retrieved successfully",
            "data" => $fine
        ], 200);
    }

    public function store (Request $request){
        $validator = Validator::make($request->all(), [
            'borrow_record_id' => 'required|exists:borrow_records,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:unpaid,paid',
            'payment_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "message" => "Validation failed",
                "errors" => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $fine = Fine::create([
            'borrow_record_id' => $validated['borrow_record_id'],
            'amount' => $validated['amount'],
            'status' => $validated['status'],
            'payment_date' => $validated['payment_date'] ?? null,
        ]);
        return response()->json([
            "ok" => true,
            "message" => "Fine created successfully",
            "data" => $fine
        ], 201);
    }

    public function update (Request $request, $id){
        $fine = Fine::find($id);
        if(!$fine){
            return response()->json([
                "ok" => false,
                "message" => "Fine not found"
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'borrow_record_id' => 'sometimes|nullable|exists:borrow_records,id',
            'amount' => 'sometimes|nullable|numeric|min:0',
            'status' => 'sometimes|nullable|enum:unpaid,paid',
            'payment_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "message" => "Validation failed",
                "errors" => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $fine->update([
            'borrow_record_id' => $validated['borrow_record_id'] ?? $fine->borrow_record_id,
            'amount' => $validated['amount'] ?? $fine->amount,
            'status' => $validated['status'] ?? $fine->status,
            'payment_date' => $validated['payment_date'] ?? $fine->payment_date,
        ]);
        return response()->json([
            "ok" => true,
            "message" => "Fine updated successfully",
            "data" => $fine
        ], 200);
    }

    public function destroy ($id){
        $fine = Fine::find($id);
        if(!$fine){
            return response()->json([
                "ok" => false,
                "message" => "Fine not found"
            ], 404);
        }
        $fine->delete();
        return response()->json([
            "ok" => true,
            "message" => "Fine deleted successfully"
        ], 200);
    }
}
