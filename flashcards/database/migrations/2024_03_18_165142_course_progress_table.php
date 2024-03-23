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
        Schema::create( 'assigned_user_courses', function( Blueprint $table ) {
            $table->unsignedBigInteger( 'user_id' );
            $table->ulid( 'course_id' );

            $table->unsignedBigInteger( 'assigned_by' );

            $table->timestamps();
        
            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' );
            $table->foreign( 'assigned_by' )->references( 'id' )->on( 'users' );
            $table->foreign( 'course_id' )->references( 'id' )->on( 'courses' );
            $table->primary([ 'user_id', 'course_id' ]);
        } );

        Schema::create('course_progress', function (Blueprint $table) {
            $table->ulid( 'id' )->primary();

            $table->unsignedBigInteger( 'user_id' )->nullable( false );
            $table->ulid( 'course_page_id' )->nullable( false );

            $table->timestamps();

            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' );
            $table->foreign( 'course_page_id' )->references( 'id' )->on( 'course_pages' );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_courses');
        Schema::dropIfExists('course_progress');
    }
};
