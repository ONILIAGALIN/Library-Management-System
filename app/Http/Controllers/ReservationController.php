<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Validator;
class ReservationController extends Controller
{
    public function index(){
        return response()->json([
            "ok" => true,
            "message" => "Reservations retrieved successfully",
            "data" => Reservation::with('user', 'book')->get()->paginate(10)
        ], 200);
    }

    public function show ($id){
        $reservation = Reservation::with('user', 'book')->findOrFail($id);
        if(!$reservation){
            return response()->json([
                "ok" => false,
                "message" => "Reservation not found"
            ], 404);
        }
        return response()->json([
            "ok" => true,
            "message" => "Reservation retrieved successfully",
            "data" => $reservation
        ], 200);
    }

    public function store (Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'reservation_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:reservation_date',
            'status' => 'required|string|in:active,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "message" => "Validation failed",
                "errors" => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'book_id' => $validated['book_id'],
            'reservation_date' => $validated['reservation_date'],
            'return_date' => $validated['return_date'],
            'status' => $validated['status'],
        ]);
        return response()->json([
            "ok" => true,
            "message" => "Reservation created successfully",
            "data" => $reservation
        ], 201);
    }

    public function update (Request $request, $id){
        $reservation = Reservation::find($id);
        if(!$reservation){
            return response()->json([
                "ok" => false,
                "message" => "Reservation not found"
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|nullable|exists:users,id',
            'book_id' => 'sometimes|nullable|exists:books,id',
            'reservation_date' => 'sometimes|nullable|date',
            'return_date' => 'sometimes|nullable|date|after_or_equal:reservation_date',
            'status' => 'sometimes|nullable|string|in:active,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "message" => "Validation failed",
                "errors" => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $reservation->update([
            'user_id' => auth()->id(),
            'book_id' => $validated['book_id'] ?? $reservation->book_id,
            'reservation_date' => $validated['reservation_date'] ?? $reservation->reservation_date,
            'return_date' => $validated['return_date'] ?? $reservation->return_date,
            'status' => $validated['status'] ?? $reservation->status,
        ]);
        return response()->json([
            "ok" => true,
            "message" => "Reservation updated successfully",
            "data" => $reservation
        ], 200);
    }

    public function destroy ($id){
        $reservation = Reservation::find($id);
        if(!$reservation){
            return response()->json([
                "ok" => false,
                "message" => "Reservation not found"
            ], 404);
        }
        $reservation->delete();
        return response()->json([
            "ok" => true,
            "message" => "Reservation deleted successfully"
        ], 200);
    }
}
