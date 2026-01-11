<div>
    {{-- Page Header --}}
    <x-admin.page-header title="Dashboard Overview" subtitle="Welcome back! Here's what's happening with your projects.">
        <x-slot:actions>
            <x-admin.button variant="primary" icon="fas fa-plus" href="{{ route('admin.projects') }}">New Project</x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    {{-- Stats Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <x-admin.stat-card
                icon="fas fa-folder-open"
                label="Total Projects"
                :value="$totalProjects"
                trend-value="{{ $activeProjects }} active"
                trend-direction="neutral"
                variant="primary"
            />
        </div>
        <div class="col-md-6 col-lg-3">
            <x-admin.stat-card
                icon="fas fa-users"
                label="Total Clients"
                :value="$totalClients"
                trend-value="All registered clients"
                trend-direction="neutral"
                variant="secondary"
            />
        </div>
        <div class="col-md-6 col-lg-3">
            <x-admin.stat-card
                icon="fas fa-dollar-sign"
                label="Total Revenue"
                :value="'Rp ' . number_format($totalRevenue, 0, ',', '.')"
                trend-value="Total payments received"
                trend-direction="up"
                variant="success"
            />
        </div>
        <div class="col-md-6 col-lg-3">
            <x-admin.stat-card
                icon="fas fa-clock"
                label="Pending Revenue"
                :value="'Rp ' . number_format($pendingRevenue, 0, ',', '.')"
                trend-value="Outstanding payments"
                trend-direction="neutral"
                variant="warning"
            />
        </div>
    </div>

    {{-- Project Status Overview --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="modern-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-semibold mb-0" style="color: var(--text-primary);">Recent Projects</h5>
                    <a href="{{ route('admin.projects') }}" class="text-decoration-none" style="color: var(--primary-color);">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

                @if($recentProjects->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-folder-open fa-3x mb-3" style="color: var(--text-muted);"></i>
                        <p style="color: var(--text-secondary);">No projects yet. Create your first project!</p>
                        <a href="{{ route('admin.projects') }}" class="btn btn-primary-modern btn-modern">
                            <i class="fas fa-plus me-2"></i>Create Project
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-modern mb-0">
                            <thead>
                                <tr>
                                    <th>Project</th>
                                    <th>Client</th>
                                    <th>Status</th>
                                    <th>Value</th>
                                    <th>Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentProjects as $project)
                                    <tr>
                                        <td>
                                            <strong style="color: var(--text-primary);">{{ $project->project_name }}</strong>
                                            <br>
                                            <small style="color: var(--text-muted);">{{ $project->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="user-avatar" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                    {{ $project->client->initials ?? 'NA' }}
                                                </div>
                                                <span>{{ $project->client->name ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <x-admin.badge
                                                :variant="$project->status_color"
                                                :icon="match($project->status) {
                                                    'pending' => 'fas fa-clock',
                                                    'in_progress' => 'fas fa-spinner',
                                                    'completed' => 'fas fa-check-circle',
                                                    'paid' => 'fas fa-check-double',
                                                    default => 'fas fa-circle'
                                                }"
                                            >
                                                {{ $project->status_label }}
                                            </x-admin.badge>
                                        </td>
                                        <td>
                                            <strong style="color: var(--text-primary);">{{ $project->formatted_final_price }}</strong>
                                        </td>
                                        <td style="min-width: 120px;">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress progress-modern flex-grow-1" style="height: 6px;">
                                                    <div
                                                        class="progress-bar progress-bar-modern bg-{{ $project->is_paid_off ? 'success' : 'primary' }}"
                                                        style="width: {{ $project->payment_progress }}%"
                                                    ></div>
                                                </div>
                                                <small style="color: var(--text-muted); min-width: 35px;">{{ number_format($project->payment_progress, 0) }}%</small>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <div class="modern-card h-100">
                <h5 class="fw-semibold mb-4" style="color: var(--text-primary);">Project Status</h5>

                <div class="d-flex flex-column gap-3">
                    {{-- Pending --}}
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon" style="width: 40px; height: 40px; background: rgba(245, 158, 11, 0.1); color: var(--warning-color);">
                                <i class="fas fa-clock" style="font-size: 1rem;"></i>
                            </div>
                            <div>
                                <strong style="color: var(--text-primary);">Pending</strong>
                                <br>
                                <small style="color: var(--text-muted);">Awaiting start</small>
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2">{{ $projectsByStatus['pending'] }}</span>
                    </div>

                    {{-- In Progress --}}
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon" style="width: 40px; height: 40px; background: rgba(14, 165, 233, 0.1); color: var(--secondary-color);">
                                <i class="fas fa-spinner" style="font-size: 1rem;"></i>
                            </div>
                            <div>
                                <strong style="color: var(--text-primary);">In Progress</strong>
                                <br>
                                <small style="color: var(--text-muted);">Currently working</small>
                            </div>
                        </div>
                        <span class="badge bg-info text-white rounded-pill px-3 py-2">{{ $projectsByStatus['in_progress'] }}</span>
                    </div>

                    {{-- Completed --}}
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon" style="width: 40px; height: 40px; background: rgba(16, 185, 129, 0.1); color: var(--success-color);">
                                <i class="fas fa-check-circle" style="font-size: 1rem;"></i>
                            </div>
                            <div>
                                <strong style="color: var(--text-primary);">Completed</strong>
                                <br>
                                <small style="color: var(--text-muted);">Work finished</small>
                            </div>
                        </div>
                        <span class="badge bg-success text-white rounded-pill px-3 py-2">{{ $projectsByStatus['completed'] }}</span>
                    </div>

                    {{-- Paid --}}
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon" style="width: 40px; height: 40px; background: rgba(99, 102, 241, 0.1); color: var(--primary-color);">
                                <i class="fas fa-check-double" style="font-size: 1rem;"></i>
                            </div>
                            <div>
                                <strong style="color: var(--text-primary);">Paid</strong>
                                <br>
                                <small style="color: var(--text-muted);">Fully settled</small>
                            </div>
                        </div>
                        <span class="badge bg-primary text-white rounded-pill px-3 py-2">{{ $projectsByStatus['paid'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Clients Section --}}
    <div class="row g-4">
        <div class="col-12">
            <div class="modern-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-semibold mb-0" style="color: var(--text-primary);">Recent Clients</h5>
                    <a href="{{ route('admin.clients') }}" class="text-decoration-none" style="color: var(--primary-color);">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

                @if($recentClients->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x mb-3" style="color: var(--text-muted);"></i>
                        <p style="color: var(--text-secondary);">No clients yet. Add your first client!</p>
                        <a href="{{ route('admin.clients') }}" class="btn btn-primary-modern btn-modern">
                            <i class="fas fa-plus me-2"></i>Add Client
                        </a>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach($recentClients as $client)
                            <div class="col-md-6 col-lg-4 col-xl-2-4">
                                <div class="p-3 rounded-3" style="background: var(--bg-tertiary); border: 1px solid var(--border-light);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="user-avatar" style="width: 48px; height: 48px;">
                                            {{ $client->initials }}
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <strong class="d-block text-truncate" style="color: var(--text-primary);">{{ $client->name }}</strong>
                                            <small style="color: var(--text-muted);">
                                                <i class="fas fa-folder me-1"></i>{{ $client->projects_count }} project(s)
                                            </small>
                                            @if($client->phone)
                                                <br>
                                                <small style="color: var(--text-muted);">
                                                    <i class="fas fa-phone me-1"></i>{{ $client->phone }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
