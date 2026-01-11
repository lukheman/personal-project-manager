<?php

namespace App\Livewire\Public;

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.public-layout')]
class ProjectTracker extends Component
{
    public string $token;
    public ?Project $project = null;

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->project = Project::with(['client', 'features.category', 'payments'])
            ->where('public_token', $token)
            ->first();

        if (!$this->project) {
            abort(404, 'Project not found');
        }
    }

    public function getTitle(): string
    {
        return $this->project ? $this->project->project_name . ' - Project Tracker' : 'Project Tracker';
    }

    public function render()
    {
        return view('livewire.public.project-tracker', [
            'project' => $this->project,
        ]);
    }
}
