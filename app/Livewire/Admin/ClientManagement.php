<?php

namespace App\Livewire\Admin;

use App\Models\Client;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.admin.livewire-layout')]
#[Title('Manajemen Client')]
class ClientManagement extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    // Form fields
    public string $name = '';
    public string $phone = '';

    public ?int $referred_by_client_id = null;

    // State
    public ?int $editingId = null;
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $showDetailModal = false;
    public ?int $deletingId = null;
    public ?Client $viewingClient = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'referred_by_client_id' => ['nullable', 'exists:clients,id'],
        ];
    }

    public function updatedSearch(): void
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
        $client = Client::findOrFail($id);
        $this->editingId = $id;
        $this->name = $client->name;
        $this->phone = $client->phone ?? '';

        $this->referred_by_client_id = $client->referred_by_client_id;
        $this->showModal = true;
    }

    public function openDetailModal(int $id): void
    {
        $this->viewingClient = Client::with(['referrer', 'referrals', 'projects'])->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->viewingClient = null;
    }

    public function save(): void
    {
        $validated = $this->validate();

        // Prevent self-referral
        if ($this->editingId && $validated['referred_by_client_id'] == $this->editingId) {
            $this->addError('referred_by_client_id', 'Client tidak bisa mereferensikan dirinya sendiri.');
            return;
        }

        if ($this->editingId) {
            $client = Client::findOrFail($this->editingId);
            $client->update($validated);
            session()->flash('success', 'Client berhasil diupdate.');
        } else {
            Client::create($validated);
            session()->flash('success', 'Client berhasil ditambahkan.');
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
            Client::destroy($this->deletingId);
            session()->flash('success', 'Client berhasil dihapus.');
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
        $this->name = '';
        $this->phone = '';

        $this->referred_by_client_id = null;
        $this->editingId = null;
    }

    public function render()
    {
        $clients = Client::query()
            ->with(['referrer', 'referrals'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get all clients for referral dropdown (exclude current if editing)
        $allClients = Client::query()
            ->when($this->editingId, fn($q) => $q->where('id', '!=', $this->editingId))
            ->orderBy('name')
            ->get();

        return view('livewire.admin.client-management', [
            'clients' => $clients,
            'allClients' => $allClients,
        ]);
    }
}
