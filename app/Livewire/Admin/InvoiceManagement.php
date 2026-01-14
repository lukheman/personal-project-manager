<?php

namespace App\Livewire\Admin;

use App\Models\Payment;
use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.admin.livewire-layout')]
#[Title('Invoice & Pembayaran')]
class InvoiceManagement extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'project')]
    public ?int $selectedProjectId = null;

    // Invoice Modal State
    public bool $showInvoiceModal = false;
    public ?Project $invoiceProject = null;
    public float $discountToApply = 0;
    public bool $useReferralDiscount = false;

    // Payment Modal State
    public bool $showPaymentModal = false;
    public ?Project $paymentProject = null;
    public string $paymentAmount = '';
    public string $paymentMethod = 'transfer';
    public string $paymentNotes = '';
    public string $paymentDate = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->paymentDate = now()->format('Y-m-d');

        if ($this->selectedProjectId) {
            $this->openInvoiceModal($this->selectedProjectId);
        }
    }

    // === Invoice Modal ===
    public function openInvoiceModal(int $projectId): void
    {
        $this->invoiceProject = Project::with(['client.referrals', 'features.category', 'payments'])->findOrFail($projectId);
        $this->discountToApply = 0;
        $this->useReferralDiscount = false;
        $this->showInvoiceModal = true;
    }

    public function closeInvoiceModal(): void
    {
        $this->showInvoiceModal = false;
        $this->invoiceProject = null;
        $this->discountToApply = 0;
        $this->useReferralDiscount = false;
        $this->selectedProjectId = null;
    }

    public function toggleReferralDiscount(): void
    {
        $this->useReferralDiscount = !$this->useReferralDiscount;

        if ($this->useReferralDiscount && $this->invoiceProject) {
            $availableCredit = $this->invoiceProject->client->available_referral_credit;
            $basePrice = $this->invoiceProject->base_price;
            $this->discountToApply = min($availableCredit, $basePrice);
        } else {
            $this->discountToApply = 0;
        }
    }

    public function getGrandTotalProperty(): float
    {
        if (!$this->invoiceProject) {
            return 0;
        }
        return max(0, $this->invoiceProject->base_price - $this->discountToApply);
    }

    public function getFormattedGrandTotalProperty(): string
    {
        return 'Rp ' . number_format($this->grandTotal, 0, ',', '.');
    }

    public function finalizeInvoice(): void
    {
        if (!$this->invoiceProject) {
            return;
        }

        if ($this->useReferralDiscount && $this->discountToApply > 0) {
            $this->invoiceProject->client->useReferralCredit($this->discountToApply);
        }

        $this->invoiceProject->finalize($this->discountToApply);

        if ($this->invoiceProject->status === 'pending' || $this->invoiceProject->status === 'in_progress') {
            $this->invoiceProject->status = 'completed';
            $this->invoiceProject->save();
        }

        session()->flash('success', 'Invoice berhasil difinalisasi! Total: Rp ' . number_format($this->grandTotal, 0, ',', '.'));
        $this->closeInvoiceModal();
    }

    // === Payment Modal ===
    public function openPaymentModal(int $projectId): void
    {
        $this->paymentProject = Project::with(['client', 'payments'])->findOrFail($projectId);
        $this->paymentAmount = '';
        $this->paymentMethod = 'transfer';
        $this->paymentNotes = '';
        $this->paymentDate = now()->format('Y-m-d');
        $this->showPaymentModal = true;
    }

    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
        $this->paymentProject = null;
        $this->resetPaymentForm();
    }

    protected function resetPaymentForm(): void
    {
        $this->paymentAmount = '';
        $this->paymentMethod = 'transfer';
        $this->paymentNotes = '';
        $this->paymentDate = now()->format('Y-m-d');
    }

    public function addPayment(): void
    {
        $this->validate([
            'paymentAmount' => ['required', 'numeric', 'min:1'],
            'paymentMethod' => ['required', 'string'],
            'paymentDate' => ['required', 'date'],
        ]);

        if (!$this->paymentProject) {
            return;
        }

        Payment::create([
            'project_id' => $this->paymentProject->id,
            'amount' => (float) $this->paymentAmount,
            'payment_method' => $this->paymentMethod,
            'notes' => $this->paymentNotes,
            'payment_date' => $this->paymentDate,
        ]);

        // Refresh project to get updated payment info
        $this->paymentProject = Project::with(['client', 'payments'])->findOrFail($this->paymentProject->id);

        // Mark as paid if fully paid
        if ($this->paymentProject->is_paid_off && $this->paymentProject->status !== 'paid') {
            $this->paymentProject->status = 'paid';
            $this->paymentProject->save();
        }

        $this->resetPaymentForm();
        session()->flash('success', 'Pembayaran berhasil ditambahkan!');
    }

    public function deletePayment(int $paymentId): void
    {
        Payment::destroy($paymentId);

        if ($this->paymentProject) {
            $this->paymentProject = Project::with(['client', 'payments'])->findOrFail($this->paymentProject->id);

            // Update status if no longer fully paid
            if (!$this->paymentProject->is_paid_off && $this->paymentProject->status === 'paid') {
                $this->paymentProject->status = 'completed';
                $this->paymentProject->save();
            }
        }

        session()->flash('success', 'Pembayaran berhasil dihapus.');
    }

    public function markAsPaid(int $projectId): void
    {
        $project = Project::findOrFail($projectId);
        $project->status = 'paid';
        $project->save();

        session()->flash('success', 'Project telah ditandai sebagai "Paid".');
    }

    public function render()
    {
        $projects = Project::query()
            ->with(['client', 'features.category', 'payments'])
            ->whereIn('status', ['pending', 'in_progress', 'completed'])
            ->when($this->search, function ($query) {
                $query->where('project_name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('client', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'));
            })
            ->orderByRaw("FIELD(status, 'completed', 'in_progress', 'pending')")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $paidProjects = Project::query()
            ->with(['client', 'payments'])
            ->where('status', 'paid')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('livewire.admin.invoice-management', [
            'projects' => $projects,
            'paidProjects' => $paidProjects,
        ]);
    }
}

