<?php

namespace App\Models;

use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePageItem extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'title', 'order', 'type', 'content'
    ];

    public function coursePage() {
        return $this->belongsTo( CoursePage::class );
    }

    protected function content(): Attribute {
        return Attribute::make(
            get: fn ( string|null $value ) => $value,
            set: fn ( string|null $value ) => $this->purifyContentHTML( $value ),
        );
    }

    private function purifyContentHTML( string|null $content ): string {
        if ( $content == null ) return '';

        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'h2,h3,ul,ol,li,strong,b,i,a[href],p');

        $purifier = new HTMLPurifier( $config );
        return $purifier->purify( $content );
    }
}
