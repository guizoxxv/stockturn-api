<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Filters\Filterable;

class Product extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'name',
        'price',
    ];
}
