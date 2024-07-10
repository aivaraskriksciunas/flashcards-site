<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSession extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false;

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo( User::class );
    }

    public function course()
    {
        return $this->belongsTo( Course::class );
    }

    public function courseSessionPages()
    {
        return $this->hasMany( CourseSessionPage::class );
    }

    /**
     * Returns current or new session to view the course
     *
     * @param User $user
     * @param Course $course
     * @return CourseSession
     */
    public static function getUserCourseSession( User $user, Course $course ) : CourseSession
    {
        $session = CourseSession::where( 'user_id', $user->id )
            ->where( 'course_id', $course->id )
            ->first();

        if ( !$session ) {
            $session = new CourseSession();
            $session->started_at = Carbon::now();
            $session->finished_at = null;
            $session->user()->associate( $user );
            $session->course()->associate( $course );
            $session->save();
        }

        return $session;
    }
}
