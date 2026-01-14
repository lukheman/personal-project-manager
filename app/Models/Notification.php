<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'link',
        'project_id',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Notification types.
     */
    public const TYPE_DEADLINE_REMINDER = 'deadline_reminder';
    public const TYPE_DEADLINE_URGENT = 'deadline_urgent';
    public const TYPE_PROJECT_OVERDUE = 'project_overdue';
    public const TYPE_PAYMENT_RECEIVED = 'payment_received';
    public const TYPE_PROJECT_COMPLETED = 'project_completed';

    /**
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project associated with the notification.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Check if notification is read.
     */
    public function getIsReadAttribute(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(): void
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread(): void
    {
        $this->update(['read_at' => null]);
    }

    /**
     * Scope for unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for read notifications.
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope for a specific user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get icon class based on notification type.
     */
    public function getIconAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_DEADLINE_REMINDER => 'heroicon-o-clock',
            self::TYPE_DEADLINE_URGENT => 'heroicon-o-exclamation-triangle',
            self::TYPE_PROJECT_OVERDUE => 'heroicon-o-x-circle',
            self::TYPE_PAYMENT_RECEIVED => 'heroicon-o-banknotes',
            self::TYPE_PROJECT_COMPLETED => 'heroicon-o-check-circle',
            default => 'heroicon-o-bell',
        };
    }

    /**
     * Get color class based on notification type.
     */
    public function getColorAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_DEADLINE_REMINDER => 'text-yellow-500',
            self::TYPE_DEADLINE_URGENT => 'text-orange-500',
            self::TYPE_PROJECT_OVERDUE => 'text-red-500',
            self::TYPE_PAYMENT_RECEIVED => 'text-green-500',
            self::TYPE_PROJECT_COMPLETED => 'text-blue-500',
            default => 'text-gray-500',
        };
    }
}
