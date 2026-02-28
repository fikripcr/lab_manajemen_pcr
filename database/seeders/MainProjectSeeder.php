<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Project\Project;
use App\Models\Project\ProjectMember;
use App\Models\Project\ProjectTask;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon;

class MainProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('ProjectSeeder started...');

        // Truncate existing project tables for fresh seed
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ProjectTask::truncate();
        ProjectMember::truncate();
        Project::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Faker::create('id_ID');

        // Fetch all users to be potential members
        $allUsers = User::all();

        if ($allUsers->count() == 0) {
            $this->command->info('No users found. Please seed users first.');
            return;
        }

        $projectStatuses = ['planning', 'active', 'completed', 'on_hold'];
        $taskStatuses = ['todo', 'in_progress', 'review', 'done'];
        $taskPriorities = ['low', 'medium', 'high', 'urgent'];
        $positions = ['Frontend Developer', 'Backend Developer', 'UI/UX Designer', 'System Analyst', 'QA Engineer', 'DevOps'];

        for ($i = 1; $i <= 10; $i++) {
            $startDate = Carbon::now()->subDays(rand(10, 100));
            $endDate = (clone $startDate)->addDays(rand(30, 120));

            // Create Project
            $project = Project::create([
                'project_name' => 'Project ' . ucwords($faker->company),
                'project_desc' => $faker->paragraph(),
                'is_agile'     => $faker->boolean(70),
                'start_date'   => $startDate,
                'end_date'     => $endDate,
                'status'       => $faker->randomElement($projectStatuses),
            ]);

            // Assign 3-5 Members
            $numMembers = rand(3, 5);
            $selectedUsers = $allUsers->random(min($numMembers, $allUsers->count()));
            $projectMembers = []; // Store IDs for task assignment

            foreach ($selectedUsers as $index => $user) {
                $role = ($index === 0) ? 'leader' : 'member';
                $position = ($index === 0) ? 'Project Manager' : $faker->randomElement($positions);

                ProjectMember::create([
                    'project_id'     => $project->project_id,
                    'user_id'        => $user->id,
                    'role'           => $role,
                    'alias_position' => $position,
                    'rate_per_hour'  => rand(50000, 250000),
                ]);

                $projectMembers[] = $user->id;
            }

            // Generate 20 Backlogs (Tasks)
            for ($j = 1; $j <= 20; $j++) {
                $taskStartDate = (clone $startDate)->addDays(rand(0, 10));
                $taskDueDate = clone $taskStartDate->addDays(rand(2, 14));

                ProjectTask::create([
                    'project_id'  => $project->project_id,
                    'assignee_id' => $faker->randomElement($projectMembers),
                    'task_title'  => ucwords($faker->words(rand(3, 6), true)),
                    'task_desc'   => $faker->text(100),
                    'status'      => $faker->randomElement($taskStatuses),
                    'weight'      => rand(1, 10),
                    'seq'         => $j,
                    'priority'    => $faker->randomElement($taskPriorities),
                    'due_date'    => $taskDueDate,
                ]);
            }

            $this->command->info("Created Project {$i} with " . count($projectMembers) . " members and 20 tasks.");
        }

        $this->command->info('ProjectSeeder completed successfully.');
    }
}
