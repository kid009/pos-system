<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPrice extends Model
{
    protected $fillable = [
        'product_id',
        'price',
        'cost',
        'started_at',
        'ended_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'cost' => 'float',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
