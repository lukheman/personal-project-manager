<div>
    {{-- Page Header --}}
    <x-admin.page-header title="Manajemen Client" subtitle="Kelola data client dan tracking referral">
        <x-slot:actions>
            <x-admin.button variant="primary" icon="fas fa-plus" wire:click="openCreateModal">
                Tambah Client
            </x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert variant="success" title="Berhasil!" class="mb-4">
            {{ session('success') }}
        </x-admin.alert>
    @endif

    {{-- Clients Table Card --}}
    <div class="modern-card">
        {{-- Search --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Semua Client</h5>
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text" style="background: var(--input-bg); border-color: var(--border-color);">
                    <i class="fas fa-search" style="color: var(--text-muted);"></i>
                </span>
                <input type="text" class="form-control" placeholder="Cari client..."
                    wire:model.live.debounce.300ms="search" style="border-left: none;">
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Kontak</th>
                        <th>Referrer</th>
                        <th>Referrals</th>
                        <th>Saldo Diskon</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $client)
                        <tr wire:key="client-{{ $client->id }}">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar">{{ $client->initials }}</div>
                                    <div>
                                        <div class="fw-semibold" style="color: var(--text-primary);">{{ $client->name }}
                                        </div>
                                        <small class="text-muted">ID: {{ $client->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td style="color: var(--text-secondary);">
                                <div>{{ $client->phone ?? '-' }}</div>
                            </td>
                            <td>
                                @if ($client->referrer)
                                    <span class="badge-modern"
                                        style="background: rgba(99, 102, 241, 0.1); color: var(--primary-color);">
                                        <i class="fas fa-user-tag me-1"></i>{{ $client->referrer->name }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($client->referrals->count() > 0)
                                    <span class="badge-modern"
                                        style="background: rgba(16, 185, 129, 0.1); color: var(--success-color);">
                                        <i class="fas fa-users me-1"></i>{{ $client->referrals->count() }} orang
                                    </span>
                                @else
                                    <span class="text-muted">0</span>
                                @endif
                            </td>
                            <td>
                                @if ($client->available_referral_credit > 0)
                                    <span class="badge-modern"
                                        style="background: rgba(245, 158, 11, 0.1); color: var(--warning-color);">
                                        {{ $client->formatted_available_credit }}
                                    </span>
                                @else
                                    <span class="text-muted">Rp 0</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="action-btn" style="color: var(--secondary-color);"
                                        wire:click="openDetailModal({{ $client->id }})" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn action-btn-edit" wire:click="openEditModal({{ $client->id }})"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn action-btn-delete"
                                        wire:click="confirmDelete({{ $client->id }})" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-user-friends mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Belum ada client</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($clients->hasPages())
            <div class="d-flex justify-content-end mt-4">
                {{ $clients->links() }}
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div class="modal-backdrop-custom" wire:click.self="closeModal">
            <div class="modal-content-custom" wire:click.stop>
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">
                        {{ $editingId ? 'Edit Client' : 'Tambah Client Baru' }}
                    </h5>
                    <button type="button" class="modal-close-btn" wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama <span style="color: var(--danger-color);">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            wire:model="name" placeholder="Nama lengkap client">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">No. Telepon</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                            wire:model="phone" placeholder="08xxxxxxxxxx">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="referred_by_client_id" class="form-label">
                            <i class="fas fa-user-tag me-1" style="color: var(--primary-color);"></i>
                            Direkomendasikan Oleh
                        </label>
                        <select class="form-control @error('referred_by_client_id') is-invalid @enderror"
                            id="referred_by_client_id" wire:model="referred_by_client_id">
                            <option value="">-- Pilih Client (Opsional) --</option>
                            @foreach ($allClients as $referrer)
                                <option value="{{ $referrer->id }}">{{ $referrer->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Jika client ini direkomendasikan oleh client lain</small>
                        @error('referred_by_client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <x-admin.button type="button" variant="outline" wire:click="closeModal">
                            Batal
                        </x-admin.button>
                        <x-admin.button type="submit" variant="primary">
                            {{ $editingId ? 'Update' : 'Simpan' }}
                        </x-admin.button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Detail Modal --}}
    @if ($showDetailModal && $viewingClient)
        <div class="modal-backdrop-custom" wire:click.self="closeDetailModal">
            <div class="modal-content-custom" wire:click.stop style="max-width: 600px;">
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">
                        <i class="fas fa-user me-2"></i>Detail Client
                    </h5>
                    <button type="button" class="modal-close-btn" wire:click="closeDetailModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="mb-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="user-avatar" style="width: 60px; height: 60px; font-size: 1.25rem;">
                            {{ $viewingClient->initials }}
                        </div>
                        <div>
                            <h4 class="mb-0" style="color: var(--text-primary);">{{ $viewingClient->name }}</h4>
                            <small class="text-muted">Client ID: {{ $viewingClient->id }}</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Telepon</small>
                        <span style="color: var(--text-primary);">{{ $viewingClient->phone ?? '-' }}</span>
                    </div>

                    @if ($viewingClient->referrer)
                        <div class="p-3 rounded mb-3" style="background: var(--hover-bg);">
                            <small class="text-muted d-block mb-1">Direkomendasikan Oleh</small>
                            <span class="badge-modern"
                                style="background: rgba(99, 102, 241, 0.1); color: var(--primary-color);">
                                <i class="fas fa-user-tag me-1"></i>{{ $viewingClient->referrer->name }}
                            </span>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="p-3 rounded text-center" style="background: rgba(16, 185, 129, 0.1);">
                                <div style="font-size: 1.5rem; font-weight: 700; color: var(--success-color);">
                                    {{ $viewingClient->referrals->count() }}
                                </div>
                                <small class="text-muted">Orang Direferensikan</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded text-center" style="background: rgba(245, 158, 11, 0.1);">
                                <div style="font-size: 1.25rem; font-weight: 700; color: var(--warning-color);">
                                    {{ $viewingClient->formatted_available_credit }}
                                </div>
                                <small class="text-muted">Saldo Diskon</small>
                            </div>
                        </div>
                    </div>

                    @if ($viewingClient->referrals->count() > 0)
                        <div class="mb-3">
                            <small class="text-muted d-block mb-2">Daftar Referral:</small>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($viewingClient->referrals as $referral)
                                    <span class="badge-modern" style="background: var(--hover-bg); color: var(--text-primary);">
                                        {{ $referral->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div>
                        <small class="text-muted d-block mb-2">Project ({{ $viewingClient->projects->count() }}):</small>
                        @if ($viewingClient->projects->count() > 0)
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($viewingClient->projects as $project)
                                    <span class="badge-modern"
                                        style="background: rgba(99, 102, 241, 0.1); color: var(--primary-color);">
                                        {{ $project->project_name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-muted">Belum ada project</span>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <x-admin.button type="button" variant="outline" wire:click="closeDetailModal">
                        Tutup
                    </x-admin.button>
                    <x-admin.button type="button" variant="primary" wire:click="closeDetailModal"
                        x-on:click="$wire.openEditModal({{ $viewingClient->id }})">
                        <i class="fas fa-edit me-1"></i>Edit
                    </x-admin.button>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    <x-admin.confirm-modal :show="$showDeleteModal" title="Konfirmasi Hapus"
        message="Apakah Anda yakin ingin menghapus client ini? Semua data project terkait juga akan dihapus."
        on-confirm="delete" on-cancel="cancelDelete" variant="danger" icon="fas fa-exclamation-triangle">
        <x-slot:confirmButton>
            <i class="fas fa-trash-alt me-2"></i>Hapus
        </x-slot:confirmButton>
    </x-admin.confirm-modal>
</div>