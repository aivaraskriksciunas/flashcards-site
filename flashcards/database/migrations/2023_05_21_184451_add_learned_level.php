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
        Schema::table('flashcard_progress', function (Blueprint $table) {
            $table->smallInteger( 'learn_level' )->unsigned()->default( 0 )->after( 'user_id' );
            $table->date( 'next_revision' )->nullable()->default( null )->after( 'learn_level' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flashcard_progress', function (Blueprint $table) {
            $table->dropColumn( 'learn_level' );
            $table->dropColumn( 'next_revision' );
        });
    }
};
