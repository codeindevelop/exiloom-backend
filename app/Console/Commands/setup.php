<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class setup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'initilized all requirment Glasster start configs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Daata base fresh migration with seed data
        $this->call('migrate:fresh', ['--seed' => 'default']);

        // Install Laravel Passport Configuration
        $this->call('passport:install');
        
        // Return commands success message ...
        return $this->info("Exiloom is ready to start");
    }
}
