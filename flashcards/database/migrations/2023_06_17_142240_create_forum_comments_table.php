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
        Schema::create('forum_comments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger( 'user_id' )->nullable( false );
            $table->unsignedBigInteger( 'forum_post_id' )->nullable( false );
            $table->text( 'content' );

            $table->timestamps();

            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' );
            $table->foreign( 'forum_post_id' )->references( 'id' )->on( 'forum_posts' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forum_comments');
    }
};
