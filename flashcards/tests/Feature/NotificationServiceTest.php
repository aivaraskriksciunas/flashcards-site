<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\NotificationService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_creates_notification()
    {
        $user = User::factory()->create();

        $service = new NotificationService();
        $service->createNotification( $user, 'Test', 'Test content' );
        
        $this->assertCount( 1, $user->notifications()->get(), 'Notification was inserted into database' );
        
        $notification = $user->notifications()->first();
        $this->assertNull( $notification->read_date, 'Read date must be null for new message.' );
    }

    public function test_mark_notification_as_read()
    {
        $user = User::factory()->create();
        $service = new NotificationService();
        $service->createNotification( $user, 'Test', 'Test content' );
        $notification = $user->notifications()->first();

        $this->assertNull( $notification->read_date, 'Read date must be null for new message.' );
        $service->markAsRead( $notification );

        $this->assertCount( 1, $user->notifications()->get(), 'Notification count should remain the same' );

        $notification = $user->notifications()->first();
        $this->assertNotEmpty( $notification->read_date, 'Read date should be filled' );
    }
}
