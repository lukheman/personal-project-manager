<?php

namespace App\Livewire\Admin;

use App\Models\Client;
use App\Models\PriceCategory;
use App\Models\Project;
use App\Models\ProjectFeature;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.admin.livewire-layout')]
#[Title('Manajemen Project')]
class ProjectManagement extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'status')]
    public string $filterStatus = '';

    // Form fields
    public ?int $client_id = null;
    public string $project_name = '';
    public string $status = 'pending';
    public string $notes = '';

    // Feature builder
    public array $features = [];
    public ?int $selectedCategoryId = null;
    public string $featureDescription = '';
    public string $customPrice = '';

    // State
    public ?int $editingId = null;
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $showDetailModal = false;
    public ?int $deletingId = null;
    public ?Project $viewingProject = null;

    protected function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'project_name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:pending,in_progress,completed,paid'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $project = Project::with('features.category')->findOrFail($id);
        $this->editingId = $id;
        $this->client_id = $project->client_id;
        $this->project_name = $project->project_name;
        $this->status = $project->status;
        $this->notes = $project->notes ?? '';

        // Load existing features
        $this->features = $project->features->map(function ($feature) {
            return [
                'id' => $feature->id,
                'category_id' => $feature->price_category_id,
                'category_name' => $feature->category->name,
                'description' => $feature->description ?? '',
                'custom_price' => $feature->custom_price,
                'base_price' => $feature->category->base_price,
            ];
        })->toArray();

        $this->showModal = true;
    }

    public function openDetailModal(int $id): void
    {
        $this->viewingProject = Project::with(['client', 'features.category'])->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->viewingProject = null;
    }

    public function addFeature(): void
    {
        if (!$this->selectedCategoryId) {
            return;
        }

        $category = PriceCategory::find($this->selectedCategoryId);
        if (!$category) {
            return;
        }

        $this->features[] = [
            'id' => null,
            'category_id' => $category->id,
            'category_name' => $category->name,
            'description' => $this->featureDescription,
            'custom_price' => $this->customPrice !== '' ? (float) $this->customPrice : null,
            'base_price' => $category->base_price,
        ];

        // Reset feature form
        $this->selectedCategoryId = null;
        $this->featureDescription = '';
        $this->customPrice = '';
    }

    public function removeFeature(int $index): void
    {
        unset($this->features[$index]);
        $this->features = array_values($this->features);
    }

    public function getTotalPriceProperty(): float
    {
        return collect($this->features)->sum(function ($feature) {
            return $feature['custom_price'] ?? $feature['base_price'];
        });
    }

    public function getFormattedTotalPriceProperty(): string
    {
        return 'Rp ' . number_format($this->totalPrice, 0, ',', '.');
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingId) {
            $project = Project::findOrFail($this->editingId);
            $project->update($validated);

            // Sync features
            $project->features()->delete();
            foreach ($this->features as $feature) {
                $project->features()->create([
                    'price_category_id' => $feature['category_id'],
                    'description' => $feature['description'],
                    'custom_price' => $feature['custom_price'],
                ]);
            }

            session()->flash('success', 'Project berhasil diupdate.');
        } else {
            $project = Project::create($validated);

            // Create features
            foreach ($this->features as $feature) {
                $project->features()->create([
                    'price_category_id' => $feature['category_id'],
                    'description' => $feature['description'],
                    'custom_price' => $feature['custom_price'],
                ]);
            }

            session()->flash('success', 'Project berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            Project::destroy($this->deletingId);
            session()->flash('success', 'Project berhasil dihapus.');
        }

        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    protected function resetForm(): void
    {
        $this->client_id = null;
        $this->project_name = '';
        $this->status = 'pending';
        $this->notes = '';
        $this->features = [];
        $this->selectedCategoryId = null;
        $this->featureDescription = '';
        $this->customPrice = '';
        $this->editingId = null;
    }

    public function render()
    {
        $projects = Project::query()
            ->with(['client', 'features.category'])
            ->when($this->search, function ($query) {
                $query->where('project_name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('client', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'));
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $clients = Client::orderBy('name')->get();
        $categories = PriceCategory::orderBy('name')->get();

        return view('livewire.admin.project-management', [
            'projects' => $projects,
            'clients' => $clients,
            'categories' => $categories,
        ]);
    }
}
