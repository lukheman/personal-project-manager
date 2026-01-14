<div>
    {{-- Page Header --}}
    <x-admin.page-header title="Invoice & Pembayaran" subtitle="Kelola invoice, pembayaran, dan diskon referral">
    </x-admin.page-header>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert variant="success" title="Berhasil!" class="mb-4">
            {{ session('success') }}
        </x-admin.alert>
    @endif

    <div class="row">
        {{-- Active Projects --}}
        <div class="col-lg-8">
            <div class="modern-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">
                        <i class="fas fa-file-invoice me-2" style="color: var(--primary-color);"></i>
                        Project Aktif
                    </h5>
                    <div class="input-group" style="max-width: 300px;">
                        <span class="input-group-text"
                            style="background: var(--input-bg); border-color: var(--border-color);">
                            <i class="fas fa-search" style="color: var(--text-muted);"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Cari project..."
                            wire:model.live.debounce.300ms="search" style="border-left: none;">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Client</th>
                                <th>Total</th>
                                <th>Pembayaran</th>
                                <th>Status</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($projects as $project)
                                <tr wire:key="project-{{ $project->id }}">
                                    <td>
                                        <div class="fw-semibold" style="color: var(--text-primary);">
                                            {{ $project->project_name }}
                                        </div>
                                        <small class="text-muted">{{ $project->features->count() }} fitur</small>
                                    </td>
                                    <td>
                                        <span style="color: var(--text-secondary);">{{ $project->client->name }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold" style="color: var(--success-color);">
                                            {{ $project->formatted_final_price }}
                                        </span>
                                    </td>
                                    <td>
                                        {{-- Payment Progress --}}
                                        <div style="min-width: 120px;">
                                            <div class="d-flex justify-content-between mb-1">
                                                <small style="color: var(--text-secondary);">
                                                    {{ $project->formatted_total_paid }}
                                                </small>
                                                <small class="text-muted">
                                                    {{ number_format($project->payment_progress, 0) }}%
                                                </small>
                                            </div>
                                            <div class="progress" style="height: 6px; background: var(--border-color);">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ $project->payment_progress }}%; background: {{ $project->is_paid_off ? 'var(--success-color)' : 'var(--primary-color)' }};">
                                                </div>
                                            </div>
                                            @if (!$project->is_paid_off)
                                                <small class="text-muted">Sisa: {{ $project->formatted_remaining }}</small>
                                            @else
                                                <small style="color: var(--success-color);"><i class="fas fa-check-circle"></i>
                                                    Lunas</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <x-admin.badge :variant="$project->status_color">
                                            {{ $project->status_label }}
                                        </x-admin.badge>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-modern btn-primary-modern"
                                                wire:click="openInvoiceModal({{ $project->id }})" title="Invoice">
                                                <i class="fas fa-file-invoice"></i>
                                            </button>
                                            <button class="btn btn-sm"
                                                style="background: var(--success-color); color: white;"
                                                wire:click="openPaymentModal({{ $project->id }})" title="Bayar">
                                                <i class="fas fa-money-bill-wave"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox mb-2" style="font-size: 2rem;"></i>
                                            <p class="mb-0">Tidak ada project aktif</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($projects->hasPages())
                    <div class="d-flex justify-content-end mt-4">
                        {{ $projects->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Recent Paid Projects --}}
        <div class="col-lg-4">
            <div class="modern-card">
                <h5 class="mb-4" style="color: var(--text-primary); font-weight: 600;">
                    <i class="fas fa-check-circle me-2" style="color: var(--success-color);"></i>
                    Lunas
                </h5>

                @forelse ($paidProjects as $paid)
                    <div class="d-flex justify-content-between align-items-center py-2 mb-2"
                        style="border-bottom: 1px solid var(--border-color);">
                        <div>
                            <div class="fw-semibold" style="color: var(--text-primary);">{{ $paid->project_name }}</div>
                            <small class="text-muted">{{ $paid->client->name }}</small>
                        </div>
                        <div class="text-end">
                            <span class="fw-semibold" style="color: var(--success-color);">
                                {{ $paid->formatted_final_price }}
                            </span>
                            <br>
                            <small class="text-muted">{{ $paid->payments->count() }}x bayar</small>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-wallet mb-2" style="font-size: 1.5rem;"></i>
                        <p class="mb-0">Belum ada project lunas</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Invoice Modal --}}
    @if ($showInvoiceModal && $invoiceProject)
        <div class="modal-backdrop-custom" wire:click.self="closeInvoiceModal">
            <div class="modal-content-custom" wire:click.stop style="max-width: 600px;">
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">
                        <i class="fas fa-file-invoice me-2" style="color: var(--primary-color);"></i>
                        Invoice
                    </h5>
                    <button type="button" class="modal-close-btn" wire:click="closeInvoiceModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- Project Info --}}
                <div class="p-3 rounded mb-4" style="background: var(--hover-bg);">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Project</span>
                        <strong style="color: var(--text-primary);">{{ $invoiceProject->project_name }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Client</span>
                        <span style="color: var(--text-primary);">{{ $invoiceProject->client->name }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Tanggal</span>
                        <span style="color: var(--text-primary);">{{ now()->format('d F Y') }}</span>
                    </div>
                </div>

                {{-- Features List --}}
                <div class="mb-4">
                    <h6 class="mb-3" style="color: var(--text-primary);">Detail Fitur</h6>
                    @foreach ($invoiceProject->features as $feature)
                        <div class="d-flex justify-content-between align-items-center py-2"
                            style="border-bottom: 1px solid var(--border-color);">
                            <div>
                                <span style="color: var(--text-primary);">{{ $feature->category->name }}</span>
                                @if ($feature->description)
                                    <small class="text-muted d-block">{{ $feature->description }}</small>
                                @endif
                            </div>
                            <span style="color: var(--text-secondary);">
                                {{ $feature->formatted_price }}
                            </span>
                        </div>
                    @endforeach
                </div>

                {{-- Subtotal --}}
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3"
                    style="border-bottom: 2px solid var(--border-color);">
                    <span style="color: var(--text-primary); font-weight: 500;">Subtotal</span>
                    <span style="font-size: 1.1rem; font-weight: 600; color: var(--text-primary);">
                        {{ $invoiceProject->formatted_base_price }}
                    </span>
                </div>

                {{-- Referral Discount Section --}}
                @if ($invoiceProject->client->available_referral_credit > 0)
                    <div class="p-3 rounded mb-4"
                        style="background: rgba(245, 158, 11, 0.1); border: 2px dashed var(--warning-color);">
                        <div class="d-flex align-items-start gap-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-gift" style="font-size: 1.5rem; color: var(--warning-color);"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1" style="color: var(--warning-color);">Diskon Referral Tersedia!</h6>
                                <p class="mb-2 text-muted" style="font-size: 0.875rem;">
                                    Client ini berhasil mengajak <strong>{{ $invoiceProject->client->referral_count }}
                                        orang</strong>.
                                    Tersedia diskon <strong>{{ $invoiceProject->client->formatted_available_credit }}</strong>.
                                </p>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="useDiscount"
                                        wire:click="toggleReferralDiscount" {{ $useReferralDiscount ? 'checked' : '' }}
                                        style="cursor: pointer;">
                                    <label class="form-check-label" for="useDiscount"
                                        style="cursor: pointer; color: var(--text-primary);">
                                        Terapkan diskon referral
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Discount Applied --}}
                @if ($discountToApply > 0)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span style="color: var(--warning-color);">
                            <i class="fas fa-tag me-1"></i>Diskon Referral
                        </span>
                        <span style="font-weight: 600; color: var(--warning-color);">
                            - Rp {{ number_format($discountToApply, 0, ',', '.') }}
                        </span>
                    </div>
                @endif

                {{-- Grand Total --}}
                <div class="p-3 rounded mb-4"
                    style="background: rgba(16, 185, 129, 0.1); border: 2px solid var(--success-color);">
                    <div class="d-flex justify-content-between align-items-center">
                        <span style="font-size: 1.1rem; color: var(--text-primary); font-weight: 600;">
                            <i class="fas fa-calculator me-2"></i>Grand Total
                        </span>
                        <span style="font-size: 1.5rem; font-weight: 700; color: var(--success-color);">
                            {{ $this->formattedGrandTotal }}
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <x-admin.button type="button" variant="outline" wire:click="closeInvoiceModal">
                        Batal
                    </x-admin.button>
                    <x-admin.button type="button" variant="primary" wire:click="finalizeInvoice">
                        <i class="fas fa-check me-1"></i>Finalisasi Invoice
                    </x-admin.button>
                </div>
            </div>
        </div>
    @endif

    {{-- Payment Modal --}}
    @if ($showPaymentModal && $paymentProject)
        <div class="modal-backdrop-custom" wire:click.self="closePaymentModal">
            <div class="modal-content-custom" wire:click.stop style="max-width: 600px; max-height: 90vh; overflow-y: auto;">
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">
                        <i class="fas fa-money-bill-wave me-2" style="color: var(--success-color);"></i>
                        Pembayaran
                    </h5>
                    <button type="button" class="modal-close-btn" wire:click="closePaymentModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- Project Info --}}
                <div class="p-3 rounded mb-4" style="background: var(--hover-bg);">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Project</span>
                        <strong style="color: var(--text-primary);">{{ $paymentProject->project_name }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Client</span>
                        <span style="color: var(--text-primary);">{{ $paymentProject->client->name }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Tagihan</span>
                        <span
                            style="color: var(--text-primary); font-weight: 600;">{{ $paymentProject->formatted_final_price }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Sisa Tagihan</span>
                        <span
                            style="color: {{ $paymentProject->is_paid_off ? 'var(--success-color)' : 'var(--warning-color)' }}; font-weight: 600;">
                            {{ $paymentProject->formatted_remaining }}
                        </span>
                    </div>
                </div>

                {{-- Payment Progress --}}
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span style="color: var(--text-primary);">Progress Pembayaran</span>
                        <span
                            style="color: var(--text-secondary);">{{ number_format($paymentProject->payment_progress, 0) }}%</span>
                    </div>
                    <div class="progress" style="height: 10px; background: var(--border-color);">
                        <div class="progress-bar" role="progressbar"
                            style="width: {{ $paymentProject->payment_progress }}%; background: {{ $paymentProject->is_paid_off ? 'var(--success-color)' : 'var(--primary-color)' }};">
                        </div>
                    </div>
                </div>

                {{-- Payment History --}}
                @if ($paymentProject->payments->count() > 0)
                    <div class="mb-4">
                        <h6 class="mb-3" style="color: var(--text-primary);">
                            <i class="fas fa-history me-1"></i>Riwayat Pembayaran ({{ $paymentProject->payments->count() }})
                        </h6>
                        @foreach ($paymentProject->payments->sortByDesc('payment_date') as $payment)
                            <div class="d-flex justify-content-between align-items-center py-2 mb-2"
                                style="border-bottom: 1px solid var(--border-color);">
                                <div>
                                    <span style="color: var(--text-primary);">{{ $payment->formatted_amount }}</span>
                                    <small class="text-muted d-block">
                                        {{ $payment->payment_date->format('d M Y') }} • {{ ucfirst($payment->payment_method) }}
                                    </small>
                                    @if ($payment->notes)
                                        <small class="text-muted">{{ $payment->notes }}</small>
                                    @endif
                                </div>
                                <button class="btn btn-sm btn-outline-danger" wire:click="deletePayment({{ $payment->id }})"
                                    wire:confirm="Hapus pembayaran ini?">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Add Payment Form --}}
                @if (!$paymentProject->is_paid_off)
                    <div class="p-3 rounded mb-4" style="background: var(--hover-bg); border: 1px dashed var(--border-color);">
                        <h6 class="mb-3" style="color: var(--text-primary);">
                            <i class="fas fa-plus-circle me-1" style="color: var(--success-color);"></i>
                            Tambah Pembayaran
                        </h6>

                        <form wire:submit="addPayment">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Jumlah (Rp) <span
                                            style="color: var(--danger-color);">*</span></label>
                                    <input type="number" class="form-control @error('paymentAmount') is-invalid @enderror"
                                        wire:model="paymentAmount" placeholder="100000" min="1">
                                    @error('paymentAmount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Sisa: {{ $paymentProject->formatted_remaining }}</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Metode</label>
                                    <select class="form-control" wire:model="paymentMethod">
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="cash">Cash</option>
                                        <option value="ewallet">E-Wallet</option>
                                        <option value="other">Lainnya</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal <span style="color: var(--danger-color);">*</span></label>
                                    <input type="date" class="form-control @error('paymentDate') is-invalid @enderror"
                                        wire:model="paymentDate">
                                    @error('paymentDate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Catatan</label>
                                    <input type="text" class="form-control" wire:model="paymentNotes" placeholder="Opsional...">
                                </div>
                            </div>

                            <x-admin.button type="submit" variant="primary" class="w-100">
                                <i class="fas fa-plus me-1"></i>Tambah Pembayaran
                            </x-admin.button>
                        </form>
                    </div>
                @else
                    <div class="p-3 rounded mb-4 text-center" style="background: rgba(16, 185, 129, 0.1);">
                        <i class="fas fa-check-circle" style="font-size: 2rem; color: var(--success-color);"></i>
                        <h5 class="mt-2 mb-0" style="color: var(--success-color);">Pembayaran Lunas!</h5>
                    </div>
                @endif

                <div class="d-flex justify-content-end">
                    <x-admin.button type="button" variant="outline" wire:click="closePaymentModal">
                        Tutup
                    </x-admin.button>
                </div>
            </div>
        </div>
    @endif
</div>