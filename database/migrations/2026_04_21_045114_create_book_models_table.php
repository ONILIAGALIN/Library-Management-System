<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->ForeignId('author_id')->constrained()->onDelete('cascade');
            $table->ForeignId('publisher_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('isbn')->unique()->nullable();
            $table->date('published_date');
            $table->integer('available_copies')->default(0);
            $table->string("extension")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
