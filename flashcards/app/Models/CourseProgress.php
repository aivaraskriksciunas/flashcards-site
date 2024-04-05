<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseProgress extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'course_progress';

    public function coursePage()
    {
        return $this->belongsTo( CoursePage::class, 'course_page_id' );
    }

    public function user()
    {
        return $this->belongsTo( User::class );
    }
}
