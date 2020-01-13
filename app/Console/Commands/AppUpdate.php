<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;

class AppUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the projects db etc. collaborator can add custom command after changes';

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
     * @return mixed
     */
    public function handle()
    {
      $output['MigrateDB'] = Artisan::call('migrate');
      print("migrated db\n");
    }
}
