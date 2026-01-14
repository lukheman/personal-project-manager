<div>
    {{-- Project Header --}}
    <div class="modern-card mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="badge-{{ $project->status_color }}">
                        @switch($project->status)
                            @case('pending')
                                <i class="fas fa-clock"></i>
                                @break
                            @case('in_progress')
                                <i class="fas fa-spinner"></i>
                                @break
                            @case('completed')
                                <i class="fas fa-check-circle"></i>
                                @break
                            @case('paid')
                                <i class="fas fa-check-double"></i>
                                @break
                        @endswitch
                        {{ $project->status_label }}
                    </div>
                </div>
                <h1 class="h2 fw-bold mb-2" style="color: var(--text-primary);">{{ $project->project_name }}</h1>
                <p class="mb-0" style="color: var(--text-secondary);">
                    <i class="fas fa-user me-2"></i>Client: <strong>{{ $project->client->name }}</strong>
                </p>
                <p class="mb-0 mt-1" style="color: var(--text-muted);">
                    <i class="fas fa-calendar me-2"></i>Created: {{ $project->created_at->format('d F Y') }}
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="stat-box">
                    <div class="stat-value" style="color: var(--success-color);">{{ $project->formatted_final_price }}</div>
                    <div class="stat-label">Total Project Value</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Progress Section --}}
    <div class="modern-card mb-4">
        <h5 class="fw-semibold mb-4" style="color: var(--text-primary);">
            <i class="fas fa-chart-line me-2" style="color: var(--primary-color);"></i>
            Payment Progress
        </h5>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-value" style="color: var(--success-color);">{{ $project->formatted_total_paid }}</div>
                    <div class="stat-label">Total Paid</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-value" style="color: var(--warning-color);">{{ $project->formatted_remaining }}</div>
                    <div class="stat-label">Remaining</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-value" style="color: var(--primary-color);">{{ number_format($project->payment_progress, 0) }}%</div>
                    <div class="stat-label">Progress</div>
                </div>
            </div>
        </div>

        <div class="progress-modern">
            <div class="progress-bar-modern" style="width: {{ $project->payment_progress }}%;"></div>
        </div>

        @if($project->is_paid_off)
            <div class="mt-3 p-3 rounded" style="background: rgba(16, 185, 129, 0.1); border: 1px solid var(--success-color);">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-check-circle" style="color: var(--success-color); font-size: 1.25rem;"></i>
                    <span style="color: var(--success-color); font-weight: 600;">Project has been fully paid! Thank you.</span>
                </div>
            </div>
        @endif
    </div>

    <div class="row g-4">
        {{-- Features --}}
        <div class="col-lg-6">
            <div class="modern-card h-100">
                <h5 class="fw-semibold mb-4" style="color: var(--text-primary);">
                    <i class="fas fa-puzzle-piece me-2" style="color: var(--primary-color);"></i>
                    Project Features ({{ $project->features->count() }})
                </h5>

                @if($project->features->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-box-open fa-2x mb-2" style="color: var(--text-muted);"></i>
                        <p style="color: var(--text-muted);">No features defined yet</p>
                    </div>
                @else
                    <div>
                        @foreach($project->features as $feature)
                            <div class="feature-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold" style="color: var(--text-primary);">{{ $feature->category->name }}</div>
                                    @if($feature->description)
                                        <small style="color: var(--text-muted);">{{ $feature->description }}</small>
                                    @endif
                                </div>
                                <span class="fw-semibold" style="color: var(--success-color);">{{ $feature->formatted_price }}</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pricing Summary --}}
                    <div class="mt-4 p-3 rounded" style="background: var(--bg-tertiary);">
                        <div class="d-flex justify-content-between mb-2">
                            <span style="color: var(--text-secondary);">Subtotal</span>
                            <span style="color: var(--text-primary);">{{ $project->formatted_base_price }}</span>
                        </div>
                        @if($project->discount_applied > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span style="color: var(--text-secondary);">Discount</span>
                                <span style="color: var(--warning-color);">-{{ $project->formatted_discount }}</span>
                            </div>
                        @endif
                        <hr style="border-color: var(--border-color);">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold" style="color: var(--text-primary);">Total</span>
                            <span class="fw-bold" style="font-size: 1.25rem; color: var(--success-color);">{{ $project->formatted_final_price }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Payment History --}}
        <div class="col-lg-6">
            <div class="modern-card h-100">
                <h5 class="fw-semibold mb-4" style="color: var(--text-primary);">
                    <i class="fas fa-history me-2" style="color: var(--primary-color);"></i>
                    Payment History ({{ $project->payments->count() }})
                </h5>

                @if($project->payments->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-receipt fa-2x mb-2" style="color: var(--text-muted);"></i>
                        <p style="color: var(--text-muted);">No payments recorded yet</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-modern mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Method</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($project->payments->sortByDesc('payment_date') as $payment)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fas fa-check-circle" style="color: var(--success-color);"></i>
                                                <span>{{ $payment->payment_date->format('d M Y') }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge" style="background: var(--bg-primary); color: var(--text-secondary);">
                                                {{ ucfirst($payment->payment_method ?? 'Transfer') }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-semibold" style="color: var(--success-color);">{{ $payment->formatted_amount }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Payment Summary --}}
                    <div class="mt-4 p-3 rounded" style="background: var(--bg-tertiary);">
                        <div class="d-flex justify-content-between">
                            <span style="color: var(--text-secondary);">Total Payments ({{ $project->payments->count() }}x)</span>
                            <span class="fw-bold" style="color: var(--success-color);">{{ $project->formatted_total_paid }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Notes --}}
    @if($project->notes)
        <div class="modern-card mt-4">
            <h5 class="fw-semibold mb-3" style="color: var(--text-primary);">
                <i class="fas fa-sticky-note me-2" style="color: var(--primary-color);"></i>
                Notes
            </h5>
            <p style="color: var(--text-secondary); margin-bottom: 0;">{{ $project->notes }}</p>
        </div>
    @endif

    {{-- Contact Info --}}
    <div class="text-center mt-5">
        <p style="color: var(--text-muted);">
            <i class="fas fa-question-circle me-1"></i>
            Have questions about your project? Contact us for more information.
        </p>
    </div>
</div>
