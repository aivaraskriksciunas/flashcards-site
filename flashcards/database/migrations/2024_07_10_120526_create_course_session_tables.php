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
        Schema::dropIfExists( 'course_progress' );

        Schema::create('course_sessions', function (Blueprint $table) {
            $table->uuid( 'id' )->primary();

            $table->unsignedBigInteger( 'user_id' );
            $table->ulid( 'course_id' );

            $table->dateTime( 'started_at' );
            $table->dateTime( 'finished_at' )->nullable();

            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' );
            $table->foreign( 'course_id' )->references( 'id' )->on( 'courses' );
        });

        Schema::create('course_session_pages', function (Blueprint $table) {
            $table->uuid( 'id' )->primary();

            $table->uuid( 'course_session_id' );
            $table->ulid( 'course_page_id' );

            $table->dateTime( 'started_at' );

            $table->foreign( 'course_session_id' )->references( 'id' )->on( 'course_sessions' );
            $table->foreign( 'course_page_id' )->references( 'id' )->on( 'course_pages' );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists( 'course_sessions' );
        Schema::dropIfExists( 'course_session_pages' );
    }
};
