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
        Schema::create('quiz_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger( 'quiz_id' );
            $table->unsignedBigInteger( 'flashcard_id' );

            $table->dateTime( 'date_answered' )->nullable()->default( null );
            $table->boolean( 'is_correct' )->nullable()->default( null );

            $table->foreign( 'quiz_id' )->references( 'id' )->on( 'quizzes' )
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign( 'flashcard_id' )->references( 'id' )->on( 'flashcards' )
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_items');
    }
};
