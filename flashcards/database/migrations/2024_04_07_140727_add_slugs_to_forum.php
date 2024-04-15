<?php

use App\Models\ForumPost;
use Illuminate\Database\Eloquent\Collection;
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
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->string( 'slug', 200 )->after( 'title' );
        });

        ForumPost::chunk( 200, function ( Collection $posts ) {
            foreach ( $posts as $post ) {
                $post->slug = $post->makeSlug();
                $post->save();
            }
        });

        Schema::table('forum_posts', function (Blueprint $table) {
            $table->unique( 'slug' );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->dropColumn( 'slug' );
        });
    }
};
