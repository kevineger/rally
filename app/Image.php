<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Image
 *
 */
class Image extends Model {
    protected $fillable = [
        'url',
        'analysis'
    ];
}
