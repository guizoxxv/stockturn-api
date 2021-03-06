<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'size',
        'type',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function (Upload $upload) {
            // TODO: Trigger event to process file
        });
    }
}
