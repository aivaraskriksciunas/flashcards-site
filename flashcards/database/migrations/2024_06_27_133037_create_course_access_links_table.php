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
        Schema::create('course_access_links', function (Blueprint $table) {
            $table->uuid( 'id' )->primary();
            $table->uuid( 'link' )->unique();
            $table->string( 'name', 100 );

            $table->ulid( 'course_id' );
            $table->unsignedBigInteger( 'user_id' );
            $table->string( 'type', 20 );

            $table->boolean( 'user_created' )->default( true )->nullable( false );

            $table->dateTime( 'expires_at' )->nullable()->default( null );
            $table->timestamps();

            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' )->cascadeOnDelete();
            $table->foreign( 'course_id' )->references( 'id' )->on( 'courses' )->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_access_links');
    }
};
