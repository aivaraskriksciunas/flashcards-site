<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Migrate is admin as a field to account type
        DB::table( 'users' )->where( 'is_admin', 1 )->chunkById( 500, function ( Collection $users ) {
            foreach ( $users as $user ) {
                DB::table( 'users' )
                    ->where( 'id', $user->id )
                    ->update([ 'account_type' => 'admin' ]);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn( 'is_admin' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean( 'is_admin' )->default( false );
        });

        DB::table( 'users' )->where( 'account_type', 'admin' )->chunkById( 500, function ( Collection $users ) {
            foreach ( $users as $user ) {
                DB::table( 'users' )
                    ->where( 'id', $user->id )
                    ->update([ 'is_admin' => true ]);
            }
        });
    }
};
