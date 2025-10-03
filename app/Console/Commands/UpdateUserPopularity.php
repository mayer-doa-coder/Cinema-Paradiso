<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\UserPopularityService;
use App\Models\User;

class UpdateUserPopularity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-popularity {--user= : Update specific user by ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user popularity scores based on their activities and engagement';

    /**
     * Execute the console command.
     */
    public function handle(UserPopularityService $popularityService)
    {
        $userId = $this->option('user');

        if ($userId) {
            // Update specific user
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found.");
                return 1;
            }

            $score = $popularityService->updateUserPopularityScore($user);
            $this->info("Updated popularity score for {$user->name}: {$score} points");
        } else {
            // Update all users
            $this->info('Updating popularity scores for all users...');
            $updated = $popularityService->updateAllPopularityScores();
            $this->info("Successfully updated {$updated} users.");
        }

        return 0;
    }
}
