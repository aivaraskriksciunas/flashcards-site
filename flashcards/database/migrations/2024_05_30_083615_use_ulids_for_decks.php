<?php

use Illuminate\Support\Collection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $referencing_tables = [ 'flashcards', 'library_decks', 'quizzes' ];

        // Sqlite does not support foreign keys
        if ( DB::getDriverName() !== 'sqlite' ) {
            // Drop foreign key constraints
            foreach ( $referencing_tables as $foreign ) {
                Schema::table( $foreign, function ( Blueprint $table ) {
                    $table->dropForeign([ 'deck_id' ]);
                } );
            }
        }

        // Modify foreign key columns
        foreach ( $referencing_tables as $foreign ) {
            Schema::table( $foreign, function ( Blueprint $table ) {
                $table->char( 'deck_id', 26 )->change();
            } );
        }

        // Change the primary ID on the decks table
        Schema::table( 'decks', function ( Blueprint $table ) {
            $table->char( 'id' )->change();
        });

        // Add foreign key constraints
        foreach ( $referencing_tables as $foreign ) {
            Schema::table( $foreign, function ( Blueprint $table ) {
                $table->foreign( 'deck_id' )->references( 'id' )->on( 'decks' );
            } );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $referencing_tables = [ 'flashcards', 'library_decks', 'quizzes' ];

        // Sqlite does not support foreign keys
        if ( DB::getDriverName() !== 'sqlite' ) {
            // Drop foreign key constraints
            foreach ( $referencing_tables as $foreign ) {
                Schema::table( $foreign, function ( Blueprint $table ) {
                    $table->dropForeign([ 'deck_id' ]);
                } );
            }
        }

        // Modify foreign key columns
        foreach ( $referencing_tables as $foreign ) {
            Schema::table( $foreign, function ( Blueprint $table ) {
                $table->unsignedBigInteger( 'deck_id' )->change();
            } );
        }

        // Change the primary ID on the decks table
        Schema::table( 'decks', function ( Blueprint $table ) {
            $table->unsignedBigInteger( 'id' )->change();
        });

        // Add foreign key constraints
        foreach ( $referencing_tables as $foreign ) {
            Schema::table( $foreign, function ( Blueprint $table ) {
                $table->foreign( 'deck_id' )->references( 'id' )->on( 'decks' );
            } );
        }
    }
};
