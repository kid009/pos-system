<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, LogsActivity;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'brand',
        'model_number',
        'description',
        'image_path',
        'is_active',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function affiliateLinks(): HasMany
    {
        return $this->hasMany(ProductAffiliateLink::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class)->orderByDesc('started_at');
    }

    #[Override]
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
