<?php

namespace App\Models\Utils;

use App\Services\Logs\Payloads\ModelChangePayload;
use App\Services\Logs\UserActivityLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasActivityLogging {

    protected static function bootHasActivityLogging() 
    {
        static::created( function ( Model $model ) {
            static::logAction( $model, 'created' );
        });

        static::updated( function ( Model $model ) {
            static::logAction( $model, 'updated' );
        });

        static::deleted( function ( Model $model ) {
            static::logAction( $model, 'deleted' );
        });
    }

    private static function logAction( Model $model, string $action )
    {
        $model_name = Str::headline( class_basename( $model::class ) );
        $payload = new ModelChangePayload( 
            "$model_name $action",
            $model
        );

        UserActivityLogger::log( $payload );
    }

}