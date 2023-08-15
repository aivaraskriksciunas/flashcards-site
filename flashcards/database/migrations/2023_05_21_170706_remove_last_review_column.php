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
        Schema::table( 'flashcards', function ( Blueprint $table ) {
            $table->dropColumn( 'last_review' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table( 'flashcards', function ( Blueprint $table ) {
            $table->dateTime( 'last_review' )->nullable()->default( null );
        });
    }
};
