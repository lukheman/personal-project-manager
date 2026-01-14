<?php

use App\Models\Client;
use App\Models\Payment;
use App\Models\PriceCategory;
use App\Models\Project;
use App\Models\ProjectFeature;

describe('Project Model', function () {

    it('can create a project with valid data', function () {
        $client = Client::factory()->create();
        $project = Project::factory()->create([
            'client_id' => $client->id,
            'project_name' => 'Test Project',
            'status' => 'pending',
        ]);

        expect($project)->toBeInstanceOf(Project::class)
            ->and($project->project_name)->toBe('Test Project')
            ->and($project->status)->toBe('pending');
    });

    it('generates public token on creation', function () {
        $project = Project::factory()->create();

        expect($project->public_token)->not->toBeNull()
            ->and(strlen($project->public_token))->toBe(32);
    });

    it('calculates base price from features', function () {
        $category1 = PriceCategory::factory()->create(['base_price' => 100000]);
        $category2 = PriceCategory::factory()->create(['base_price' => 200000]);

        $project = Project::factory()->create();
        ProjectFeature::factory()->create([
            'project_id' => $project->id,
            'price_category_id' => $category1->id,
            'custom_price' => null,
        ]);
        ProjectFeature::factory()->create([
            'project_id' => $project->id,
            'price_category_id' => $category2->id,
            'custom_price' => null,
        ]);

        expect($project->base_price)->toBe(300000.0);
    });

    it('uses custom price when set in features', function () {
        $category = PriceCategory::factory()->create(['base_price' => 100000]);

        $project = Project::factory()->create();
        ProjectFeature::factory()->create([
            'project_id' => $project->id,
            'price_category_id' => $category->id,
            'custom_price' => 150000,
        ]);

        expect($project->base_price)->toBe(150000.0);
    });

    it('can be soft deleted', function () {
        $project = Project::factory()->create();
        $projectId = $project->id;

        $project->delete();

        expect(Project::find($projectId))->toBeNull()
            ->and(Project::withTrashed()->find($projectId))->not->toBeNull();
    });

    it('calculates deadline status correctly', function () {
        // No deadline
        $noDeadline = Project::factory()->create(['deadline' => null]);
        expect($noDeadline->deadline_status)->toBe('none');

        // Completed project
        $completed = Project::factory()->create([
            'deadline' => now()->addDays(5),
            'status' => 'completed',
        ]);
        expect($completed->deadline_status)->toBe('completed');

        // Overdue
        $overdue = Project::factory()->create([
            'deadline' => now()->subDays(1),
            'status' => 'in_progress',
        ]);
        expect($overdue->deadline_status)->toBe('overdue');

        // Urgent (1-3 days)
        $urgent = Project::factory()->create([
            'deadline' => now()->addDays(2),
            'status' => 'in_progress',
        ]);
        expect($urgent->deadline_status)->toBe('urgent');

        // Warning (4-7 days)
        $warning = Project::factory()->create([
            'deadline' => now()->addDays(5),
            'status' => 'in_progress',
        ]);
        expect($warning->deadline_status)->toBe('warning');

        // Normal (>7 days)
        $normal = Project::factory()->create([
            'deadline' => now()->addDays(10),
            'status' => 'pending',
        ]);
        expect($normal->deadline_status)->toBe('normal');
    });

    it('calculates days until deadline correctly', function () {
        $project = Project::factory()->create([
            'deadline' => now()->addDays(5),
        ]);

        expect($project->days_until_deadline)->toBe(5);
    });

    it('detects overdue projects', function () {
        $overdue = Project::factory()->create([
            'deadline' => now()->subDay(),
            'status' => 'in_progress',
        ]);

        expect($overdue->is_overdue)->toBeTrue();

        $notOverdue = Project::factory()->create([
            'deadline' => now()->addDay(),
            'status' => 'in_progress',
        ]);

        expect($notOverdue->is_overdue)->toBeFalse();
    });

    it('returns correct status labels', function () {
        expect(Project::factory()->create(['status' => 'pending'])->status_label)->toBe('Pending')
            ->and(Project::factory()->create(['status' => 'in_progress'])->status_label)->toBe('In Progress')
            ->and(Project::factory()->create(['status' => 'completed'])->status_label)->toBe('Completed')
            ->and(Project::factory()->create(['status' => 'paid'])->status_label)->toBe('Paid');
    });

    it('calculates payment progress correctly', function () {
        $project = Project::factory()->create(['final_price' => 1000000]);
        Payment::factory()->create([
            'project_id' => $project->id,
            'amount' => 500000,
        ]);

        expect($project->payment_progress)->toBe(50.0);
    });

    it('calculates remaining amount correctly', function () {
        $project = Project::factory()->create(['final_price' => 1000000]);
        Payment::factory()->create([
            'project_id' => $project->id,
            'amount' => 300000,
        ]);

        expect($project->remaining_amount)->toBe(700000.0);
    });

    it('detects fully paid projects', function () {
        $project = Project::factory()->create(['final_price' => 1000000]);
        Payment::factory()->create([
            'project_id' => $project->id,
            'amount' => 1000000,
        ]);

        expect($project->is_paid_off)->toBeTrue();
    });
});
