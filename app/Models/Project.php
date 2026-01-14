<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'project_name',
        'status',
        'final_price',
        'discount_applied',
        'notes',
        'public_token',
        'deadline',
    ];

    protected $casts = [
        'final_price' => 'decimal:2',
        'discount_applied' => 'decimal:2',
        'deadline' => 'date',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->public_token)) {
                $project->public_token = Str::random(32);
            }
        });
    }

    /**
     * Get the public URL for this project.
     */
    public function getPublicUrlAttribute(): string
    {
        return url('/project/' . $this->public_token);
    }

    /**
     * Check if project is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        if (!$this->deadline) {
            return false;
        }
        return $this->deadline->isPast() && !in_array($this->status, ['completed', 'paid']);
    }

    /**
     * Get days until deadline (negative if overdue).
     */
    public function getDaysUntilDeadlineAttribute(): ?int
    {
        if (!$this->deadline) {
            return null;
        }
        return now()->startOfDay()->diffInDays($this->deadline, false);
    }

    /**
     * Get deadline status for UI badge.
     */
    public function getDeadlineStatusAttribute(): string
    {
        if (!$this->deadline) {
            return 'none';
        }

        if (in_array($this->status, ['completed', 'paid'])) {
            return 'completed';
        }

        $days = $this->days_until_deadline;

        if ($days < 0) {
            return 'overdue';
        } elseif ($days <= 3) {
            return 'urgent';
        } elseif ($days <= 7) {
            return 'warning';
        }

        return 'normal';
    }

    /**
     * Get formatted deadline.
     */
    public function getFormattedDeadlineAttribute(): ?string
    {
        if (!$this->deadline) {
            return null;
        }
        return $this->deadline->format('d M Y');
    }
    /**
     * Get the client for this project.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get all features for this project.
     */
    public function features(): HasMany
    {
        return $this->hasMany(ProjectFeature::class);
    }

    /**
     * Get all attachments for this project.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(ProjectAttachment::class);
    }

    /**
     * Get all notifications for this project.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Calculate base price from all features.
     */
    public function calculateBasePrice(): float
    {
        return $this->features->sum(function ($feature) {
            return $feature->custom_price ?? $feature->category->base_price;
        });
    }

    /**
     * Get base price attribute.
     */
    public function getBasePriceAttribute(): float
    {
        return $this->calculateBasePrice();
    }

    /**
     * Get formatted base price.
     */
    public function getFormattedBasePriceAttribute(): string
    {
        return 'Rp ' . number_format($this->base_price, 0, ',', '.');
    }

    /**
     * Get formatted final price.
     */
    public function getFormattedFinalPriceAttribute(): string
    {
        $price = $this->final_price ?? $this->base_price;
        return 'Rp ' . number_format($price, 0, ',', '.');
    }

    /**
     * Get formatted discount.
     */
    public function getFormattedDiscountAttribute(): string
    {
        return 'Rp ' . number_format($this->discount_applied, 0, ',', '.');
    }

    /**
     * Get status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'in_progress' => 'info',
            'completed' => 'success',
            'paid' => 'primary',
            default => 'secondary',
        };
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'paid' => 'Paid',
            default => 'Unknown',
        };
    }

    /**
     * Finalize project with discount.
     */
    public function finalize(float $discount = 0): void
    {
        $this->discount_applied = $discount;
        $this->final_price = max(0, $this->base_price - $discount);
        $this->save();
    }

    /**
     * Get all payments for this project.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get total amount paid.
     */
    public function getTotalPaidAttribute(): float
    {
        return (float) $this->payments->sum('amount');
    }

    /**
     * Get remaining amount to be paid.
     */
    public function getRemainingAmountAttribute(): float
    {
        $total = $this->final_price ?? $this->base_price;
        return max(0, $total - $this->total_paid);
    }

    /**
     * Check if project is fully paid.
     */
    public function getIsPaidOffAttribute(): bool
    {
        return $this->remaining_amount <= 0;
    }

    /**
     * Get formatted total paid.
     */
    public function getFormattedTotalPaidAttribute(): string
    {
        return 'Rp ' . number_format($this->total_paid, 0, ',', '.');
    }

    /**
     * Get formatted remaining amount.
     */
    public function getFormattedRemainingAttribute(): string
    {
        return 'Rp ' . number_format($this->remaining_amount, 0, ',', '.');
    }

    /**
     * Get payment progress percentage.
     */
    public function getPaymentProgressAttribute(): float
    {
        $total = $this->final_price ?? $this->base_price;
        if ($total <= 0) {
            return 100;
        }
        return min(100, ($this->total_paid / $total) * 100);
    }
}
