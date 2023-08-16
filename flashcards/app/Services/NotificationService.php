<?php 

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class NotificationService 
{
    /**
     * Create a notification for the specified user
     *
     * @param User $owner
     * @param string $title
     * @param string $content
     * @return Notification
     */
    public function createNotification( User $owner, string $title, string $content )
    {
        $notification = new Notification([ 'title' => $title, 'content' => $content ]);
        $notification->user()->associate( $owner );
        $notification->save();

        return $notification;
    }

    /**
     * Mark the given notification as read
     *
     * @param Notification $notification
     * @return Notification notification with the read date set
     */
    public function markAsRead( Notification $notification )
    {
        $notification->read_date = Carbon::now();
        $notification->save();

        return $notification;
    }
}