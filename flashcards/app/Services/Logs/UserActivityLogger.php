<?php 

namespace App\Services\Logs;

use App\Models\UserLog;
use App\Services\Logs\Payloads\LoggerPayload;

class UserActivityLogger 
{

    /**
     * Creates a new log entry in the database for the currently logged in user
     *
     * @param string $action
     * @param LoggerPayload $payload
     * @return void
     */
    public static function log( LoggerPayload $payload )
    {
        if ( request()->user() == null ) return;
        
        $log = new UserLog();
        $log->user()->associate( request()->user() );
        $log->action = $payload->getAction();
        $log->payload = $payload->toJson();
        
        $model = $payload->getModel();
        if ( $model ) {
            $log->object_type = $model::class;
            $log->object_id = $model->id;
        }

        $log->save();
    }
}