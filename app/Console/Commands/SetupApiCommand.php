<?php

// app/Console/Commands/SetupApiCommand.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupApiCommand extends Command
{
    protected $signature = 'api:setup';
    protected $description = 'Setup API for Next.js connection';

    public function handle()
    {
        $this->info('Setting up API for Next.js...');

        // Create storage link
        $this->info('Creating storage link...');
        Artisan::call('storage:link');

        // Clear config cache
        $this->info('Clearing config cache...');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');

        // Seed test data
        $this->info('Seeding test data...');
        Artisan::call('db:seed', ['--class' => 'ApiTestDataSeeder']);

        $this->info('API setup completed!');
        $this->info('Your API is now ready at: ' . config('app.url') . '/api');
        $this->info('Test endpoint: ' . config('app.url') . '/api/health');
    }
}