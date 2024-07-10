<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSessionPage extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false;

    public function coursePage()
    {
        return $this->belongsTo( CoursePage::class );
    }

    public function courseSession()
    {
        return $this->belongsTo( CourseSession::class );
    }
}
