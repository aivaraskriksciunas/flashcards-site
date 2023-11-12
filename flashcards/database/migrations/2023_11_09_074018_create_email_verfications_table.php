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
        Schema::create('email_verifications', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger( 'user_id' )->nullable( false );
            $table->string( 'verification_code', 200 )->unique();
            $table->date( 'valid_until' );

            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' );
            $table->timestamps();
        });

        Schema::table( 'users', function( Blueprint $table ) {
            $table->boolean( 'is_valid' )
                ->default( true )
                ->after( 'is_admin' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_verifications');
        Schema::table( 'users', function( Blueprint $table ) {
            $table->dropColumn( 'is_valid' );
        });
    }
};
