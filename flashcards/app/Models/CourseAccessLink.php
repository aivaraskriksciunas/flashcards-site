<?php

namespace App\Models;

use App\Enums\CourseAccessLinkType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CourseAccessLink extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'expires_at',
        'name',
        'user_created',
        'type',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'user_created' => 'boolean',
        'type' => CourseAccessLinkType::class,
    ];

    public static function booted() 
    {
        static::creating( function ( CourseAccessLink $accessLink ) {
            if ( $accessLink->link == null ) {
                $accessLink->generateUniqueLink();
            }
        });
    }

    public function user() {
        return $this->belongsTo( User::class );
    }

    public function course() {
        return $this->belongsTo( Course::class );
    }

    /**
     * Generates and sets a unique identifier
     *
     * @return string
     */
    public function generateUniqueLink() : string {
        $this->link = Str::uuid()->toString();
        return $this->link;
    }

    public function getRouteKeyName(): string
    {
        return 'link';
    }
}
