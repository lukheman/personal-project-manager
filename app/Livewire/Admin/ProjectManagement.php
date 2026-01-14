<?php

namespace App\Livewire\Admin;

use App\Models\Client;
use App\Models\PriceCategory;
use App\Models\Project;
use App\Models\ProjectAttachment;
use App\Models\ProjectFeature;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('components.admin.livewire-layout')]
#[Title('Manajemen Project')]
class ProjectManagement extends Component
{
    use WithPagination, WithFileUploads;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'status')]
    public string $filterStatus = '';

    // Form fields
    public ?int $client_id = null;
    public string $project_name = '';
    public string $status = 'pending';
    public string $notes = '';
    public ?string $deadline = null;

    // Feature builder
    public array $features = [];
    public ?int $selectedCategoryId = null;
    public string $featureDescription = '';
    public string $customPrice = '';

    // File uploads
    public $newAttachments = [];
    public array $existingAttachments = [];

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
            'deadline' => ['nullable', 'date'],
            'newAttachments.*' => ['nullable', 'file', 'max:10240'], // 10MB max
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
        $project = Project::with(['features.category', 'attachments'])->findOrFail($id);
        $this->editingId = $id;
        $this->client_id = $project->client_id;
        $this->project_name = $project->project_name;
        $this->status = $project->status;
        $this->notes = $project->notes ?? '';
        $this->deadline = $project->deadline?->format('Y-m-d');

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

        // Load existing attachments
        $this->existingAttachments = $project->attachments->map(function ($attachment) {
            return [
                'id' => $attachment->id,
                'filename' => $attachment->filename,
                'size' => $attachment->formatted_size,
                'download_url' => $attachment->download_url,
            ];
        })->toArray();

        $this->showModal = true;
    }

    public function openDetailModal(int $id): void
    {
        $this->viewingProject = Project::with(['client', 'features.category', 'attachments'])->findOrFail($id);
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

    public function removeExistingAttachment(int $attachmentId): void
    {
        ProjectAttachment::destroy($attachmentId);
        $this->existingAttachments = array_filter($this->existingAttachments, function ($att) use ($attachmentId) {
            return $att['id'] !== $attachmentId;
        });
        $this->existingAttachments = array_values($this->existingAttachments);
    }

    public function removeNewAttachment(int $index): void
    {
        if (isset($this->newAttachments[$index])) {
            unset($this->newAttachments[$index]);
            $this->newAttachments = array_values($this->newAttachments);
        }
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

        $projectData = [
            'client_id' => $validated['client_id'],
            'project_name' => $validated['project_name'],
            'status' => $validated['status'],
            'notes' => $validated['notes'],
            'deadline' => $validated['deadline'] ?: null,
        ];

        if ($this->editingId) {
            $project = Project::findOrFail($this->editingId);
            $project->update($projectData);

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
            $project = Project::create($projectData);

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

        // Handle file uploads
        if (!empty($this->newAttachments)) {
            foreach ($this->newAttachments as $file) {
                $path = $file->store('project-attachments', 'public');
                $project->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
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
        $this->deadline = null;
        $this->features = [];
        $this->selectedCategoryId = null;
        $this->featureDescription = '';
        $this->customPrice = '';
        $this->editingId = null;
        $this->newAttachments = [];
        $this->existingAttachments = [];
    }

    public function render()
    {
        $projects = Project::query()
            ->with(['client', 'features.category', 'attachments'])
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

