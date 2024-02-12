<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    use HasFactory, HasUlids;

    protected $casts = [
        'payload' => 'array',
        'created_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo( User::class );
    }
}
