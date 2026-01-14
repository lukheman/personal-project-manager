<?php

namespace App\Livewire;

use App\Models\Notification;
use Illuminate\Support\Collection;
use Livewire\Component;

class NotificationBell extends Component
{
    public bool $showDropdown = false;

    protected $listeners = ['notificationAdded' => '$refresh'];

    /**
     * Get count of unread notifications.
     */
    public function getUnreadCountProperty(): int
    {
        return Notification::forUser(auth()->id())
            ->unread()
            ->count();
    }

    /**
     * Get recent notifications.
     */
    public function getNotificationsProperty(): Collection
    {
        return Notification::forUser(auth()->id())
            ->with('project')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Toggle dropdown visibility.
     */
    public function toggleDropdown(): void
    {
        $this->showDropdown = !$this->showDropdown;
    }

    /**
     * Close dropdown.
     */
    public function closeDropdown(): void
    {
        $this->showDropdown = false;
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(int $id): void
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->markAsRead();
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(): void
    {
        Notification::forUser(auth()->id())
            ->unread()
            ->update(['read_at' => now()]);
    }

    /**
     * Delete a notification.
     */
    public function deleteNotification(int $id): void
    {
        Notification::where('user_id', auth()->id())
            ->where('id', $id)
            ->delete();
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
