<?php

use App\Models\Client;
use App\Models\Payment;
use App\Models\Project;

describe('Payment Model', function () {

    it('can create a payment with valid data', function () {
        $project = Project::factory()->create();
        $payment = Payment::factory()->create([
            'project_id' => $project->id,
            'amount' => 500000,
            'payment_method' => 'transfer',
            'payment_date' => now(),
        ]);

        expect($payment)->toBeInstanceOf(Payment::class)
            ->and($payment->amount)->toBe('500000.00')
            ->and($payment->payment_method)->toBe('transfer');
    });

    it('belongs to a project', function () {
        $project = Project::factory()->create();
        $payment = Payment::factory()->create(['project_id' => $project->id]);

        expect($payment->project)->toBeInstanceOf(Project::class)
            ->and($payment->project->id)->toBe($project->id);
    });

    it('formats amount correctly', function () {
        $payment = Payment::factory()->create(['amount' => 1500000]);

        expect($payment->formatted_amount)->toBe('Rp 1.500.000');
    });

    it('can be soft deleted', function () {
        $payment = Payment::factory()->create();
        $paymentId = $payment->id;

        $payment->delete();

        expect(Payment::find($paymentId))->toBeNull()
            ->and(Payment::withTrashed()->find($paymentId))->not->toBeNull();
    });

    it('can be restored after soft delete', function () {
        $payment = Payment::factory()->create();
        $paymentId = $payment->id;

        $payment->delete();
        $payment->restore();

        expect(Payment::find($paymentId))->not->toBeNull()
            ->and($payment->deleted_at)->toBeNull();
    });

    it('updates project payment totals correctly', function () {
        $project = Project::factory()->create(['final_price' => 1000000]);

        Payment::factory()->create([
            'project_id' => $project->id,
            'amount' => 300000,
        ]);
        Payment::factory()->create([
            'project_id' => $project->id,
            'amount' => 200000,
        ]);

        $project->refresh();

        expect($project->total_paid)->toBe(500000.0)
            ->and($project->remaining_amount)->toBe(500000.0);
    });

    it('marks project as paid when fully paid', function () {
        $project = Project::factory()->create([
            'final_price' => 500000,
            'status' => 'completed',
        ]);

        Payment::factory()->create([
            'project_id' => $project->id,
            'amount' => 500000,
        ]);

        $project->refresh();

        expect($project->is_paid_off)->toBeTrue();
    });
});
