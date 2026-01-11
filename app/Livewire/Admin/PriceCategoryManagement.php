<?php

namespace App\Livewire\Admin;

use App\Models\PriceCategory;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.admin.livewire-layout')]
#[Title('Kategori Harga')]
class PriceCategoryManagement extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    // Form fields
    public string $name = '';
    public string $base_price = '';
    public string $description = '';

    // State
    public ?int $editingId = null;
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
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
        $category = PriceCategory::findOrFail($id);
        $this->editingId = $id;
        $this->name = $category->name;
        $this->base_price = (string) $category->base_price;
        $this->description = $category->description ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingId) {
            $category = PriceCategory::findOrFail($this->editingId);
            $category->update($validated);
            session()->flash('success', 'Kategori berhasil diupdate.');
        } else {
            PriceCategory::create($validated);
            session()->flash('success', 'Kategori berhasil ditambahkan.');
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
            PriceCategory::destroy($this->deletingId);
            session()->flash('success', 'Kategori berhasil dihapus.');
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
        $this->base_price = '';
        $this->description = '';
        $this->editingId = null;
    }

    public function render()
    {
        $categories = PriceCategory::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.price-category-management', [
            'categories' => $categories,
        ]);
    }
}
