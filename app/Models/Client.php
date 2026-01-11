<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'referred_by_client_id',
        'referral_credit_used',
    ];

    protected $casts = [
        'referral_credit_used' => 'decimal:2',
    ];

    /**
     * Get the client who referred this client.
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'referred_by_client_id');
    }

    /**
     * Get all clients referred by this client.
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(Client::class, 'referred_by_client_id');
    }

    /**
     * Get all projects for this client.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Calculate available referral credit (100k per referral minus used).
     */
    public function getAvailableReferralCreditAttribute(): float
    {
        $referralCount = $this->referrals()->count();
        $totalPotential = $referralCount * 100000;

        return max(0, $totalPotential - $this->referral_credit_used);
    }

    /**
     * Get total referral count.
     */
    public function getReferralCountAttribute(): int
    {
        return $this->referrals()->count();
    }

    /**
     * Format available credit for display.
     */
    public function getFormattedAvailableCreditAttribute(): string
    {
        return 'Rp ' . number_format($this->available_referral_credit, 0, ',', '.');
    }

    /**
     * Use referral credit for a discount.
     */
    public function useReferralCredit(float $amount): bool
    {
        if ($amount > $this->available_referral_credit) {
            return false;
        }

        $this->referral_credit_used += $amount;
        $this->save();

        return true;
    }

    /**
     * Get initials for avatar.
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';

        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }

        return $initials;
    }
}
