<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:create-test-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test user for development purposes.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::factory()->create();

        $this->info('Test user created');
        $this->info('password: password');
        $this->info('email: '.$user->email);
    }
}
