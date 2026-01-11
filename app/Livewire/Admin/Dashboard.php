<?php

namespace App\Livewire\Admin;

use App\Models\Client;
use App\Models\Payment;
use App\Models\Project;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.admin.livewire-layout')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        // Get statistics
        $totalProjects = Project::count();
        $activeProjects = Project::whereIn('status', ['pending', 'in_progress'])->count();
        $completedProjects = Project::where('status', 'completed')->count();
        $paidProjects = Project::where('status', 'paid')->count();
        $totalClients = Client::count();
        $totalUsers = User::count();

        // Calculate total revenue (from payments)
        $totalRevenue = Payment::sum('amount');

        // Calculate pending revenue (projects completed but not fully paid)
        $pendingRevenue = Project::with('payments')
            ->whereIn('status', ['completed', 'in_progress'])
            ->get()
            ->sum(function ($project) {
                return $project->remaining_amount;
            });

        // Get recent projects
        $recentProjects = Project::with(['client', 'features.category'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get project status distribution
        $projectsByStatus = [
            'pending' => Project::where('status', 'pending')->count(),
            'in_progress' => Project::where('status', 'in_progress')->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'paid' => Project::where('status', 'paid')->count(),
        ];

        // Get recent clients
        $recentClients = Client::withCount('projects')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('livewire.admin.dashboard', [
            'totalProjects' => $totalProjects,
            'activeProjects' => $activeProjects,
            'completedProjects' => $completedProjects,
            'paidProjects' => $paidProjects,
            'totalClients' => $totalClients,
            'totalUsers' => $totalUsers,
            'totalRevenue' => $totalRevenue,
            'pendingRevenue' => $pendingRevenue,
            'recentProjects' => $recentProjects,
            'projectsByStatus' => $projectsByStatus,
            'recentClients' => $recentClients,
        ]);
    }

    /**
     * Format currency to Indonesian Rupiah
     */
    public function formatCurrency(float $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
