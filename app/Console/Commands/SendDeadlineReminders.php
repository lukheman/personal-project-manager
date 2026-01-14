<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Project;
use App\Models\User;
use Illuminate\Console\Command;

class SendDeadlineReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-deadline-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send internal notifications for upcoming and overdue deadlines';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking project deadlines...');

        $users = User::all();
        $notificationsCreated = 0;

        // Get projects with deadlines
        $activeProjects = Project::query()
            ->whereNotNull('deadline')
            ->whereIn('status', ['pending', 'in_progress'])
            ->with('client')
            ->get();

        foreach ($activeProjects as $project) {
            $daysUntil = $project->days_until_deadline;

            // Skip if no deadline or already has recent notification of same type
            if ($daysUntil === null) {
                continue;
            }

            $notificationType = null;
            $title = null;
            $message = null;

            // Overdue projects
            if ($daysUntil < 0) {
                $notificationType = Notification::TYPE_PROJECT_OVERDUE;
                $title = 'Project Overdue!';
                $message = "Project \"{$project->project_name}\" untuk {$project->client->name} sudah melewati deadline " . abs($daysUntil) . " hari yang lalu.";
            }
            // Urgent: 1-3 days remaining
            elseif ($daysUntil <= 3) {
                $notificationType = Notification::TYPE_DEADLINE_URGENT;
                $title = 'Deadline Mendesak!';
                $message = "Project \"{$project->project_name}\" deadline dalam {$daysUntil} hari lagi ({$project->formatted_deadline}).";
            }
            // Warning: 4-7 days remaining
            elseif ($daysUntil <= 7) {
                $notificationType = Notification::TYPE_DEADLINE_REMINDER;
                $title = 'Pengingat Deadline';
                $message = "Project \"{$project->project_name}\" deadline dalam {$daysUntil} hari lagi ({$project->formatted_deadline}).";
            }

            if ($notificationType) {
                // Check if similar notification already exists today
                $existingNotification = Notification::where('project_id', $project->id)
                    ->where('type', $notificationType)
                    ->whereDate('created_at', today())
                    ->exists();

                if (!$existingNotification) {
                    // Create notification for all admin users
                    foreach ($users as $user) {
                        Notification::create([
                            'user_id' => $user->id,
                            'type' => $notificationType,
                            'title' => $title,
                            'message' => $message,
                            'link' => route('admin.projects') . '?q=' . urlencode($project->project_name),
                            'project_id' => $project->id,
                        ]);
                        $notificationsCreated++;
                    }

                    $this->line("  → Created notification for: {$project->project_name}");
                }
            }
        }

        $this->info("Done! Created {$notificationsCreated} notifications.");

        return Command::SUCCESS;
    }
}
