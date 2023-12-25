<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'name', 'type'
    ];

    public function user() {
        return $this->hasOne( User::class );
    }
}
