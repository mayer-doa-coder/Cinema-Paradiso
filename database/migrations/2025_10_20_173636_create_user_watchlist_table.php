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
        Schema::create('user_watchlist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('movie_id'); // TMDB movie ID
            $table->string('movie_title');
            $table->string('movie_poster')->nullable();
            $table->year('release_year')->nullable();
            $table->timestamps();
            
            // Unique constraint to prevent duplicate entries
            $table->unique(['user_id', 'movie_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_watchlist');
    }
};
