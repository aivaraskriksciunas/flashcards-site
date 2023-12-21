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
        Schema::table( 'users', function (Blueprint $table) {
            // Allow nullable emails for subaccounts
            $table->string( 'email', 50 )->nullable( true )->change();
            $table->string( 'password', 200 )->nullable( true )->change();

            $table->string( 'account_type', 10 )
                ->default( 'student' )
                ->nullable( false )
                ->after( 'is_valid' );

            // Create a pointer to parent account
            $table->unsignedBigInteger( 'parent_id' )
                ->nullable()
                ->default( null )
                ->after( 'name' );
            $table->foreign( 'parent_id' )->references( 'id' )->on( 'users' );
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table( 'users', function (Blueprint $table) {
            $table->string( 'email', 50 )->nullable( false )->change();
            $table->string( 'password', 200 )->nullable( false )->change();
            $table->dropColumn( 'account_type' );
            $table->dropForeign([ 'parent_id' ]);
            $table->dropColumn( 'parent_id' );
        });
    }
};
