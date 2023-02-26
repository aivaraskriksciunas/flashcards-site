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
        Schema::create( 'libraries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger( 'user_id' )->nullable( false );
            $table->string( 'name', 100 );
            $table->timestamps();

            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' );
        });

        Schema::create( 'library_decks', function (Blueprint $table) {
            $table->unsignedBigInteger( 'library_id' )->nullable( false );
            $table->unsignedBigInteger( 'deck_id' )->nullable( false );
            $table->dateTime( 'created_at' )->nullable( false );
            $table->dateTime( 'last_view_at' )->nullable();

            $table->primary([ 'library_id', 'deck_id' ]);
            $table->foreign( 'library_id' )->references( 'id' )->on( 'libraries' );
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
        Schema::dropIfExists('library_decks');
        Schema::dropIfExists('libraries');
    }
};
