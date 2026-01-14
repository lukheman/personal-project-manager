<div>
    {{-- Page Header --}}
    <x-admin.page-header title="Kategori Harga" subtitle="Kelola daftar harga standar layanan">
        <x-slot:actions>
            <x-admin.button variant="primary" icon="fas fa-plus" wire:click="openCreateModal">
                Tambah Kategori
            </x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert variant="success" title="Berhasil!" class="mb-4">
            {{ session('success') }}
        </x-admin.alert>
    @endif

    {{-- Categories Table Card --}}
    <div class="modern-card">
        {{-- Search --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Semua Kategori</h5>
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text" style="background: var(--input-bg); border-color: var(--border-color);">
                    <i class="fas fa-search" style="color: var(--text-muted);"></i>
                </span>
                <input type="text" class="form-control" placeholder="Cari kategori..."
                    wire:model.live.debounce.300ms="search" style="border-left: none;">
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr wire:key="category-{{ $category->id }}">
                            <td>
                                <div class="fw-semibold" style="color: var(--text-primary);">{{ $category->name }}</div>
                            </td>
                            <td>
                                <span class="badge-modern"
                                    style="background: rgba(16, 185, 129, 0.1); color: var(--success-color);">
                                    {{ $category->formatted_price }}
                                </span>
                            </td>
                            <td style="color: var(--text-secondary); max-width: 300px;">
                                {{ Str::limit($category->description, 50) ?? '-' }}
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="action-btn action-btn-edit"
                                        wire:click="openEditModal({{ $category->id }})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn action-btn-delete"
                                        wire:click="confirmDelete({{ $category->id }})" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-tags mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Belum ada kategori harga</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($categories->hasPages())
            <div class="d-flex justify-content-end mt-4">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div class="modal-backdrop-custom" wire:click.self="closeModal">
            <div class="modal-content-custom" wire:click.stop>
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">
                        {{ $editingId ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
                    </h5>
                    <button type="button" class="modal-close-btn" wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori <span
                                style="color: var(--danger-color);">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            wire:model="name" placeholder="Contoh: CRUD Sederhana">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="base_price" class="form-label">Harga (Rp) <span
                                style="color: var(--danger-color);">*</span></label>
                        <input type="number" class="form-control @error('base_price') is-invalid @enderror" id="base_price"
                            wire:model="base_price" placeholder="30000" min="0" step="1000">
                        @error('base_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                            wire:model="description" placeholder="Deskripsi kategori (opsional)" rows="3"></textarea>
                        @error('description')
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

    {{-- Delete Confirmation Modal --}}
    <x-admin.confirm-modal :show="$showDeleteModal" title="Konfirmasi Hapus"
        message="Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan."
        on-confirm="delete" on-cancel="cancelDelete" variant="danger" icon="fas fa-exclamation-triangle">
        <x-slot:confirmButton>
            <i class="fas fa-trash-alt me-2"></i>Hapus
        </x-slot:confirmButton>
    </x-admin.confirm-modal>
</div>