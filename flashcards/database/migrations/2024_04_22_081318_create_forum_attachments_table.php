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
        Schema::create('forum_attachments', function (Blueprint $table) {
            $table->ulid( 'id')->primary();

            $table->unsignedBigInteger( 'forum_post_id' );
            $table->string( 'attachable_id', 40 );
            $table->string( 'attachable_type', 40 );

            $table->string( 'title', 200 );

            $table->timestamps();

            $table->foreign( 'forum_post_id' )->references( 'id' )->on( 'forum_posts' );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_attachments');
    }
};
