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
        Schema::create('flashcards', function (Blueprint $table) {
            $table->id();

            $table->text( 'question' );
            $table->text( 'answer' );

            $table->unsignedBigInteger( 'deck_id' )->nullable( false );

            $table->dateTime( 'last_review' );

            $table->timestamps();

            $table->foreign( 'deck_id' )->references( 'id' )->on( 'decks' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flashcards');
    }
};
