<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StockMovement extends Model
{
    use HasUuids, LogsActivity;

    // Mass Assignable fields (Protected from financial manipulation)
    protected $fillable = [
        'product_id',
        'warehouse_id',
        'user_id',
        'type',
        'qty',
        'unit_cost',
        'reference',
    ];

    protected $hidden = [
        'id'
    ];

    protected $casts = [
        'qty' => 'integer',
        'unit_cost' => 'float',
        'created_at' => 'datetime',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Intercept updating/deleting events to strictly enforce append-only immutability.
     */
    #[Override]
    public static function booted(): void
    {
        static::updating(function ($movement) {
            throw new \Exception("Architectural Violation: Stock movements are an unalterable ledger and cannot be updated.");
        });

        static::deleting(function ($movement) {
            throw new \Exception("Architectural Violation: Stock movements are an unalterable ledger and cannot be deleted.");
        });
    }

    /**
     * Configure Enterprise-Grade Activity Logging parameters.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Track all assignable operational transaction states
            ->logFillable()
            // Isolate namespace to prevent collision with master product logging
            ->useLogName('inventory_ledger');
    }
}
