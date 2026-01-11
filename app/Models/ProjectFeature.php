<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectFeature extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'price_category_id',
        'description',
        'custom_price',
    ];

    protected $casts = [
        'custom_price' => 'decimal:2',
    ];

    /**
     * Get the project for this feature.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the price category for this feature.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(PriceCategory::class, 'price_category_id');
    }

    /**
     * Get the effective price (custom or from category).
     */
    public function getEffectivePriceAttribute(): float
    {
        return $this->custom_price ?? $this->category->base_price;
    }

    /**
     * Get formatted effective price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->effective_price, 0, ',', '.');
    }
}
