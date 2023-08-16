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
        Schema::create('flashcard_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger( 'flashcard_id' )->nullable( false );
            $table->unsignedBigInteger( 'user_id' )->nullable( false );

            // Number of times user guessed this card correctly
            $table->unsignedSmallInteger( 'ncorrect' )->default( 0 );
            // Number of times user guessed this card incorrectly
            $table->unsignedSmallInteger( 'nwrong' )->default( 0 );

            $table->dateTime( 'last_review' )->nullable()->default( null );

            // Stores whether last answer was answered incorrectly
            $table->boolean( 'is_last_wrong' )->default( 0 );

            $table->foreign( 'flashcard_id' )->references( 'id' )->on( 'flashcards' )
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' )
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flashcard_progress');
    }
};
