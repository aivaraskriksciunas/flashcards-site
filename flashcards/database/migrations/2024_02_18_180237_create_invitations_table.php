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
        Schema::create('invitations', function (Blueprint $table) {
            $table->uuid( 'id' );
            $table->uuid( 'code' )->unique();

            $table->string( 'email', 100 );
            $table->string( 'name', 200 );
            $table->string( 'account_type', 200 );
            $table->unsignedBigInteger( 'creator_id' );
            $table->ulid( 'organization_id' );
            $table->dateTime( 'valid_until' );

            $table->timestamps();

            $table->foreign( 'creator_id' )
                ->references( 'id' )
                ->on( 'users' );

            $table->foreign( 'organization_id' )
                ->references( 'id' )
                ->on( 'organizations' )
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
