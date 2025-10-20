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
        Schema::create('user_movie_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('movie_id'); // TMDB movie ID
            $table->string('movie_title');
            $table->string('movie_poster')->nullable();
            $table->decimal('rating', 3, 1); // User's rating (0.0 - 10.0)
            $table->boolean('watched_before')->default(false);
            $table->text('review');
            $table->year('release_year')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_movie_reviews');
    }
};
