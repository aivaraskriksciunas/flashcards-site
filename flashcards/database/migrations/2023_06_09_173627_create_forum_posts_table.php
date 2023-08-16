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
        Schema::create('forum_posts', function (Blueprint $table) {
            $table->id();

            $table->string( 'title', 150 );
            $table->unsignedBigInteger( 'user_id' )->nullable( false );
            $table->unsignedBigInteger( 'forum_topic_id' )->nullable( false );
            $table->text( 'content' );

            $table->timestamps();

            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' );
            $table->foreign( 'forum_topic_id' )->references( 'id' )->on( 'forum_topics' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forum_posts');
    }
};
