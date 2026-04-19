<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/********** Authentication Routes **********/
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);

/********** User Routes **********/
Route::prefix("users")->group(function () {
    Route::post("/", [App\Http\Controllers\UserController::class, "store"]);
    Route::get("/", [App\Http\Controllers\UserController::class, "index"]);
    Route::get("/{user}", [App\Http\Controllers\UserController::class, "show"]);
    Route::patch("/{user}", [App\Http\Controllers\UserController::class, "update"]);
    Route::delete("/{user}", [App\Http\Controllers\UserController::class, "destroy"]);
});

/********** Author Routes **********/
Route::prefix("authors")->group(function () {
    Route::post("/", [App\Http\Controllers\AuthorController::class, "store"]);
    Route::get("/", [App\Http\Controllers\AuthorController::class, "index"]);
    Route::get("/{author}", [App\Http\Controllers\AuthorController::class, "show"]);
    Route::patch("/{author}", [App\Http\Controllers\AuthorController::class, "update"]);
    Route::delete("/{author}", [App\Http\Controllers\AuthorController::class, "destroy"]);
});

/********** Publisher Routes **********/
Route::prefix("publishers")->group(function () {
    Route::post("/", [App\Http\Controllers\PublisherController::class, "store"]);
    Route::get("/", [App\Http\Controllers\PublisherController::class, "index"]);
    Route::get("/{publisher}", [App\Http\Controllers\PublisherController::class, "show"]);
    Route::patch("/{publisher}", [App\Http\Controllers\PublisherController::class, "update  "]);
    Route::delete("/{publisher}", [App\Http\Controllers\PublisherController::class, "destroy"]);
});

/********** Category Routes **********/
Route::prefix("categories")->group(function () {
    Route::post("/", [App\Http\Controllers\CategoryController::class, "store"]);
    Route::get("/", [App\Http\Controllers\CategoryController::class, "index"]);
    Route::get("/{category}", [App\Http\Controllers\CategoryController::class, "show"]);
    Route::patch("/{category}", [App\Http\Controllers\CategoryController::class, "update"]);
    Route::delete("/{category}", [App\Http\Controllers\CategoryController::class, "destroy"]);
});

/********** Book Routes **********/
Route::prefix("books")->group(function () {
    Route::post("/", [App\Http\Controllers\BookController::class, "store"]);
    Route::get("/", [App\Http\Controllers\BookController::class, "index"]);
    Route::get("/{book}", [App\Http\Controllers\BookController::class, "show"]);
    Route::patch("/{book}", [App\Http\Controllers\BookController::class, "update"]);
    Route::delete("/{book}", [App\Http\Controllers\BookController::class, "destroy"]);
});

/********** Reservation Routes **********/
Route::prefix("reservations")->group(function () {
    Route::post("/", [App\Http\Controllers\ReservationController::class, "store"]);
    Route::get("/", [App\Http\Controllers\ReservationController::class, "index"]);
    Route::get("/{reservation}", [App\Http\Controllers\ReservationController::class, "show"]);
    Route::patch("/{reservation}", [App\Http\Controllers\ReservationController::class, "update"]);
    Route::delete("/{reservation}", [App\Http\Controllers\ReservationController::class, "destroy"]);
});

/********** Borrow Record Routes **********/
Route::prefix("borrow-records")->group(function () {
    Route::post("/", [App\Http\Controllers\BorrowRecordController::class, "store"]);
    Route::get("/", [App\Http\Controllers\BorrowRecordController::class, "index"]);
    Route::get("/{borrow_record}", [App\Http\Controllers\BorrowRecordController::class, "show"]);
    Route::patch("/{borrow_record}", [App\Http\Controllers\BorrowRecordController::class, "update"]);
    Route::delete("/{borrow_record}", [App\Http\Controllers\BorrowRecordController::class, "destroy"]);
}); 

/********** Fine Routes **********/
Route::prefix("fines")->group(function () {
    Route::post("/", [App\Http\Controllers\FineController::class, "store"]);
    Route::get("/", [App\Http\Controllers\FineController::class, "index"]);
    Route::get("/{fine}", [App\Http\Controllers\FineController::class, "show"]);
    Route::patch("/{fine}", [App\Http\Controllers\FineController::class, "update"]);
    Route::delete("/{fine}", [App\Http\Controllers\FineController::class, "destroy"]);
});