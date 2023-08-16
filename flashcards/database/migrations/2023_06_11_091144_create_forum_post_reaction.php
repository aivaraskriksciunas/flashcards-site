<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Services\ForumReactions\ForumReactions;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_reactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger( 'user_id' );
            
            $table->unsignedBigInteger( 'reactable_id' );
            $table->string( 'reactable_type', 30 );

            $table->enum(
                'type',
                [ 
                    ForumReactions::Upvote->value,
                    ForumReactions::Downvote->value,
                ]
            );
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
        Schema::dropIfExists('forum_reactions');
    }
};
