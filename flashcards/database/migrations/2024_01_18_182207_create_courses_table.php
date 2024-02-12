<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->ulid( 'id' )->primary();

            $table->string( 'title', 150 );
            $table->unsignedBigInteger( 'user_id' );

            $table->timestamps();
            $table->softDeletes();

            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' );
        });

        Schema::create( 'course_pages', function ( Blueprint $table ) {
            $table->ulid( 'id' )->primary();
            $table->ulid( 'course_id' );

            $table->string( 'title', 150 );
            $table->string( 'type', 15 )->nullable();
            $table->smallInteger( 'order' )->unsigned();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign( 'course_id' )->references( 'id' )->on( 'courses' );
        });

        Schema::create( 'course_page_items', function ( Blueprint $table ) {
            $table->ulid( 'id' )->primary();
            $table->ulid( 'course_page_id' );

            $table->string( 'type', 15 )->nullable();
            $table->string( 'title', 150 )->nullable();
            $table->text( 'content' )->nullable();
            $table->smallInteger( 'order' )->unsigned();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign( 'course_page_id' )->references( 'id' )->on( 'course_pages' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'course_page_items' );
        Schema::dropIfExists( 'course_pages' );
        Schema::dropIfExists( 'courses' );
    }
};
