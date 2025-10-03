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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('popularity_score')->default(0)->after('bio');
            $table->string('platform')->nullable()->after('popularity_score'); // tmdb, imdb, letterboxd, etc.
            $table->string('platform_username')->nullable()->after('platform');
            $table->string('location')->nullable()->after('platform_username');
            $table->json('social_links')->nullable()->after('location'); // twitter, instagram, etc.
            $table->integer('total_movies_watched')->default(0)->after('social_links');
            $table->integer('total_reviews')->default(0)->after('total_movies_watched');
            $table->boolean('is_public')->default(true)->after('total_reviews');
            $table->timestamp('last_active')->nullable()->after('is_public');
        });

        // Create user favorite movies table
        Schema::create('user_favorite_movies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('movie_id'); // TMDb movie ID
            $table->string('movie_title');
            $table->string('movie_poster')->nullable();
            $table->decimal('user_rating', 3, 1)->nullable(); // User's personal rating
            $table->text('user_review')->nullable();
            $table->timestamp('watched_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'movie_id']);
            $table->index(['user_id', 'created_at']);
        });

        // Create user followers table
        Schema::create('user_followers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('follower_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('following_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['follower_id', 'following_id']);
            $table->index(['follower_id']);
            $table->index(['following_id']);
        });

        // Create user activities table for tracking popularity
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('activity_type'); // review, favorite, follow, etc.
            $table->json('activity_data')->nullable();
            $table->integer('points')->default(0); // Points for popularity calculation
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index(['activity_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'popularity_score',
                'platform',
                'platform_username', 
                'location',
                'social_links',
                'total_movies_watched',
                'total_reviews',
                'is_public',
                'last_active'
            ]);
        });
        
        Schema::dropIfExists('user_activities');
        Schema::dropIfExists('user_followers');
        Schema::dropIfExists('user_favorite_movies');
    }
};
