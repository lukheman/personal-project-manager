<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PriceCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'base_price',
        'description',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
    ];

    /**
     * Get all project features using this category.
     */
    public function projectFeatures(): HasMany
    {
        return $this->hasMany(ProjectFeature::class);
    }

    /**
     * Format price for display (Indonesian Rupiah).
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->base_price, 0, ',', '.');
    }
}
