<div class="relative" x-data="{ open: @entangle('showDropdown') }">
    {{-- Bell Icon Button --}}
    <button 
        @click="open = !open"
        class="relative p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg transition-colors"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        
        {{-- Badge --}}
        @if($this->unreadCount > 0)
            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
                {{ $this->unreadCount > 9 ? '9+' : $this->unreadCount }}
            </span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div 
        x-show="open" 
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50"
        style="display: none;"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 dark:bg-gray-750 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Notifikasi</h3>
            @if($this->unreadCount > 0)
                <button 
                    wire:click="markAllAsRead"
                    class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium"
                >
                    Tandai semua dibaca
                </button>
            @endif
        </div>

        {{-- Notification List --}}
        <div class="max-h-96 overflow-y-auto">
            @forelse($this->notifications as $notification)
                <div 
                    class="relative px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-750 border-b border-gray-100 dark:border-gray-700 last:border-0 transition-colors {{ !$notification->is_read ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}"
                >
                    <div class="flex items-start gap-3">
                        {{-- Icon --}}
                        <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full {{ $notification->color }} bg-gray-100 dark:bg-gray-700">
                            @switch($notification->type)
                                @case('deadline_reminder')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @break
                                @case('deadline_urgent')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    @break
                                @case('project_overdue')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @break
                                @case('payment_received')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    @break
                                @default
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                            @endswitch
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ $notification->title }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2">
                                {{ $notification->message }}
                            </p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex-shrink-0 flex items-center gap-1">
                            @if(!$notification->is_read)
                                <button 
                                    wire:click="markAsRead({{ $notification->id }})"
                                    class="p-1 text-gray-400 hover:text-blue-500 transition-colors"
                                    title="Tandai dibaca"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            @endif
                            <button 
                                wire:click="deleteNotification({{ $notification->id }})"
                                class="p-1 text-gray-400 hover:text-red-500 transition-colors"
                                title="Hapus"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Link to project --}}
                    @if($notification->link)
                        <a 
                            href="{{ $notification->link }}" 
                            wire:click="markAsRead({{ $notification->id }})"
                            class="absolute inset-0"
                        ></a>
                    @endif
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Tidak ada notifikasi</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
