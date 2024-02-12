<?php

namespace App\Models;

use App\Models\Utils\HasActivityLogging;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory, HasUlids, HasActivityLogging;

    protected $fillable = [
        'name', 'type'
    ];

    public function user() {
        return $this->hasOne( User::class );
    }
}
