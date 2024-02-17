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
        Schema::table('flashcards', function (Blueprint $table) {
            $table->string( 'question_type', 15 )
                ->default( 'text' )
                ->after( 'deck_id' );

            $table->string( 'answer_type', 15 )
                ->default( 'text' )
                ->after( 'question_type' );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flashcards', function (Blueprint $table) {
            $table->dropColumn( 'question_type' );
            $table->dropColumn( 'answer_type' );
        });
    }
};
