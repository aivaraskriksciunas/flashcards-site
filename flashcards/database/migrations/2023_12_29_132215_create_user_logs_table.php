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
        Schema::create('user_logs', function (Blueprint $table) {
            $table->ulid( 'id' );

            $table->unsignedBigInteger( 'user_id' )->nullable( false );
            $table->string( 'action', 50 );
            $table->string( 'object_type', 40 )->nullable();
            $table->string( 'object_id', 40 )->nullable();
            $table->json( 'payload' )->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('user_logs');
    }
};
