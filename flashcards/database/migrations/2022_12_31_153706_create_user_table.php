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
        Schema::create( 'users', function (Blueprint $table) {
            $table->id();
            
            $table->string( 'email', 50 )->unique();
            $table->string( 'password', 200 );
            $table->string( 'name', 100 );

            $table->boolean( 'is_admin' )->default( false );

            $table->rememberToken();

            $table->dateTime( 'last_login' )->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
