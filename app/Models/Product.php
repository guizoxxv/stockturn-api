<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Filters\Filterable;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'name',
        'price',
        'stock',
    ];

    protected $casts = [
        'price' => 'float',
        'stockTimeline' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Product $product) {
            $product->sku = uniqid();
        });

        static::updating(function (Product $product) {
            if ($product->isDirty('stock')) {
                $stockTimeline = $product->stockTimeline ?? [];

                $timelineItem = [
                    'stock' => $product->stock,
                    'date' => Carbon::now()->format('Y-m-d H:i'),
                ];

                array_push($stockTimeline, $timelineItem);

                $product->stockTimeline = $stockTimeline;
            }
        });
    }
}
