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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger( 'deck_id' );
            $table->unsignedBigInteger( 'user_id' );

            $table->dateTime( 'date_generated' );
            $table->dateTime( 'date_taken' )->nullable()->default( null );

            $table->foreign( 'deck_id' )->references( 'id' )->on( 'decks' );
            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quizzes');
    }
};
