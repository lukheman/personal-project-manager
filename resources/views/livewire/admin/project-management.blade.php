<div>
    {{-- Page Header --}}
    <x-admin.page-header title="Manajemen Project" subtitle="Kelola project dan estimasi harga">
        <x-slot:actions>
            <x-admin.button variant="primary" icon="fas fa-plus" wire:click="openCreateModal">
                Buat Project
            </x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert variant="success" title="Berhasil!" class="mb-4">
            {{ session('success') }}
        </x-admin.alert>
    @endif

    {{-- Projects Table Card --}}
    <div class="modern-card">
        {{-- Search and Filter --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Semua Project</h5>
            <div class="d-flex gap-3">
                <select class="form-control" wire:model.live="filterStatus" style="min-width: 150px;">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="paid">Paid</option>
                </select>
                <div class="input-group" style="max-width: 300px;">
                    <span class="input-group-text"
                        style="background: var(--input-bg); border-color: var(--border-color);">
                        <i class="fas fa-search" style="color: var(--text-muted);"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Cari project..."
                        wire:model.live.debounce.300ms="search" style="border-left: none;">
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Client</th>
                        <th>Fitur</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projects as $project)
                        <tr wire:key="project-{{ $project->id }}">
                            <td>
                                <div class="fw-semibold" style="color: var(--text-primary);">{{ $project->project_name }}
                                </div>
                                <small class="text-muted">{{ $project->created_at->format('d M Y') }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                        {{ $project->client->initials }}
                                    </div>
                                    <span style="color: var(--text-secondary);">{{ $project->client->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-modern"
                                    style="background: rgba(99, 102, 241, 0.1); color: var(--primary-color);">
                                    {{ $project->features->count() }} fitur
                                </span>
                            </td>
                            <td>
                                <span class="fw-semibold" style="color: var(--success-color);">
                                    {{ $project->formatted_base_price }}
                                </span>
                                @if ($project->discount_applied > 0)
                                    <br>
                                    <small class="text-muted">
                                        <del>{{ $project->formatted_base_price }}</del>
                                        <span style="color: var(--warning-color);">-{{ $project->formatted_discount }}</span>
                                    </small>
                                @endif
                            </td>
                            <td>
                                <x-admin.badge :variant="$project->status_color">
                                    {{ $project->status_label }}
                                </x-admin.badge>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="action-btn" style="color: var(--secondary-color);"
                                        wire:click="openDetailModal({{ $project->id }})" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn action-btn-edit"
                                        wire:click="openEditModal({{ $project->id }})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn action-btn-delete"
                                        wire:click="confirmDelete({{ $project->id }})" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-folder-open mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Belum ada project</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($projects->hasPages())
            <div class="d-flex justify-content-end mt-4">
                {{ $projects->links() }}
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div class="modal-backdrop-custom" wire:click.self="closeModal">
            <div class="modal-content-custom" wire:click.stop style="max-width: 900px; max-height: 90vh; overflow-y: auto;">
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">
                        {{ $editingId ? 'Edit Project' : 'Buat Project Baru' }}
                    </h5>
                    <button type="button" class="modal-close-btn" wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit="save">
                    {{-- Basic Info --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="client_id" class="form-label">Client <span
                                    style="color: var(--danger-color);">*</span></label>
                            <select class="form-control @error('client_id') is-invalid @enderror" id="client_id"
                                wire:model="client_id">
                                <option value="">-- Pilih Client --</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status"
                                wire:model="status">
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="paid">Paid</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="project_name" class="form-label">Nama Project <span
                                style="color: var(--danger-color);">*</span></label>
                        <input type="text" class="form-control @error('project_name') is-invalid @enderror"
                            id="project_name" wire:model="project_name" placeholder="Contoh: Aplikasi SPK AHP">
                        @error('project_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea class="form-control" id="notes" wire:model="notes" rows="2"
                            placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>

                    {{-- Feature Builder --}}
                    <div class="p-3 rounded mb-4"
                        style="background: var(--hover-bg); border: 1px dashed var(--border-color);">
                        <h6 class="mb-3" style="color: var(--text-primary);">
                            <i class="fas fa-puzzle-piece me-2" style="color: var(--primary-color);"></i>
                            Tambah Fitur
                        </h6>

                        <div class="row g-2 mb-2">
                            <div class="col-md-4">
                                <select class="form-control" wire:model="selectedCategoryId">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}
                                            ({{ $category->formatted_price }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" wire:model="featureDescription"
                                    placeholder="Deskripsi (opsional)">
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control" wire:model="customPrice" placeholder="Custom Rp"
                                    min="0">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary-modern w-100" wire:click="addFeature">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">Custom price bersifat opsional, kosongkan untuk menggunakan harga
                            kategori</small>
                    </div>

                    {{-- Features List --}}
                    @if (count($features) > 0)
                        <div class="mb-4">
                            <h6 class="mb-3" style="color: var(--text-primary);">
                                <i class="fas fa-list me-2"></i>Daftar Fitur ({{ count($features) }})
                            </h6>
                            <div class="list-group">
                                @foreach ($features as $index => $feature)
                                    <div class="list-group-item d-flex justify-content-between align-items-center"
                                        wire:key="feature-{{ $index }}"
                                        style="background: var(--bg-secondary); border-color: var(--border-color); color: var(--text-primary);">
                                        <div>
                                            <strong>{{ $feature['category_name'] }}</strong>
                                            @if ($feature['description'])
                                                <span class="text-muted"> - {{ $feature['description'] }}</span>
                                            @endif
                                            @if ($feature['custom_price'])
                                                <br><small class="text-muted"><del>Rp
                                                        {{ number_format($feature['base_price'], 0, ',', '.') }}</del></small>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="fw-semibold" style="color: var(--success-color);">
                                                Rp
                                                {{ number_format($feature['custom_price'] ?? $feature['base_price'], 0, ',', '.') }}
                                            </span>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                wire:click="removeFeature({{ $index }})">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Total Price --}}
                    <div class="p-3 rounded mb-4"
                        style="background: rgba(16, 185, 129, 0.1); border: 2px solid var(--success-color);">
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="font-size: 1.1rem; color: var(--text-primary);">
                                <i class="fas fa-calculator me-2"></i>Total Estimasi
                            </span>
                            <span style="font-size: 1.5rem; font-weight: 700; color: var(--success-color);">
                                {{ $this->formattedTotalPrice }}
                            </span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <x-admin.button type="button" variant="outline" wire:click="closeModal">
                            Batal
                        </x-admin.button>
                        <x-admin.button type="submit" variant="primary">
                            {{ $editingId ? 'Update Project' : 'Simpan Project' }}
                        </x-admin.button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Detail Modal --}}
    @if ($showDetailModal && $viewingProject)
        <div class="modal-backdrop-custom" wire:click.self="closeDetailModal">
            <div class="modal-content-custom" wire:click.stop style="max-width: 800px; max-height: 90vh; overflow-y: auto;">
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">
                        <i class="fas fa-folder me-2"></i>Detail Project
                    </h5>
                    <button type="button" class="modal-close-btn" wire:click="closeDetailModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4 class="mb-1" style="color: var(--text-primary);">{{ $viewingProject->project_name }}</h4>
                            <span class="text-muted">{{ $viewingProject->created_at->format('d F Y') }}</span>
                        </div>
                        <x-admin.badge :variant="$viewingProject->status_color">
                            {{ $viewingProject->status_label }}
                        </x-admin.badge>
                    </div>

                    <div class="p-3 rounded mb-3" style="background: var(--hover-bg);">
                        <small class="text-muted d-block mb-1">Client</small>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                {{ $viewingProject->client->initials }}
                            </div>
                            <span
                                style="color: var(--text-primary); font-weight: 500;">{{ $viewingProject->client->name }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-2">Daftar Fitur
                            ({{ $viewingProject->features->count() }})</small>
                        @foreach ($viewingProject->features as $feature)
                            <div class="d-flex justify-content-between align-items-center py-2"
                                style="border-bottom: 1px solid var(--border-color);">
                                <div>
                                    <span style="color: var(--text-primary);">{{ $feature->category->name }}</span>
                                    @if ($feature->description)
                                        <small class="text-muted d-block">{{ $feature->description }}</small>
                                    @endif
                                </div>
                                <span class="fw-semibold" style="color: var(--success-color);">
                                    {{ $feature->formatted_price }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="p-3 rounded" style="background: rgba(16, 185, 129, 0.1);">
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color: var(--text-primary); font-weight: 500;">Total</span>
                            <span style="font-size: 1.25rem; font-weight: 700; color: var(--success-color);">
                                {{ $viewingProject->formatted_base_price }}
                            </span>
                        </div>
                        @if ($viewingProject->discount_applied > 0)
                            <div class="d-flex justify-content-between align-items-center mt-2 pt-2"
                                style="border-top: 1px solid var(--border-color);">
                                <span class="text-muted">Diskon</span>
                                <span style="color: var(--warning-color);">-{{ $viewingProject->formatted_discount }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span style="color: var(--text-primary); font-weight: 600;">Grand Total</span>
                                <span style="font-size: 1.25rem; font-weight: 700; color: var(--primary-color);">
                                    {{ $viewingProject->formatted_final_price }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Public Link --}}
                    <div class="p-3 rounded mb-3" style="background: rgba(99, 102, 241, 0.1); border: 1px solid var(--primary-color);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="d-block" style="color: var(--text-muted);">
                                    <i class="fas fa-link me-1"></i>Public Link untuk Client
                                </small>
                                <input type="text" class="form-control form-control-sm mt-1" readonly
                                    value="{{ $viewingProject->public_url }}"
                                    id="publicUrl-{{ $viewingProject->id }}"
                                    style="background: var(--bg-secondary); font-size: 0.85rem;">
                            </div>
                            <button type="button" class="btn btn-sm btn-primary-modern ms-2"
                                onclick="copyToClipboard('publicUrl-{{ $viewingProject->id }}')"
                                title="Salin Link">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    @if ($viewingProject->notes)
                        <div class="mt-3">
                            <small class="text-muted d-block mb-1">Catatan</small>
                            <p style="color: var(--text-secondary);">{{ $viewingProject->notes }}</p>
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <x-admin.button type="button" variant="outline" wire:click="closeDetailModal">
                        Tutup
                    </x-admin.button>
                    <a href="{{ route('admin.invoices') }}?project={{ $viewingProject->id }}"
                        class="btn btn-modern btn-primary-modern">
                        <i class="fas fa-file-invoice me-1"></i>Buat Invoice
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    <x-admin.confirm-modal :show="$showDeleteModal" title="Konfirmasi Hapus"
        message="Apakah Anda yakin ingin menghapus project ini? Semua data fitur terkait juga akan dihapus."
        on-confirm="delete" on-cancel="cancelDelete" variant="danger" icon="fas fa-exclamation-triangle">
        <x-slot:confirmButton>
            <i class="fas fa-trash-alt me-2"></i>Hapus
        </x-slot:confirmButton>
    </x-admin.confirm-modal>
</div>