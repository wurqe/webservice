<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Artisan;

class AppReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the app by refreshing db, force reintsall passport and ';

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
      Log::debug('app:reset');
      $output['freshDb'] = Artisan::call('migrate:fresh');
      print("refreshed migration\n");
      $output['passportDb'] = Artisan::call('migrate', [
        '--path' => 'vendor/laravel/passport/database/migrations', '--force' => true
      ]);
      print("migrated passport\n");
      $output['passportInstall'] = Artisan::call('passport:install');
      print("installed passport\n");
      $output['ImportData'] = Artisan::call('db:import_default_data');
      print("imported default database datas\n");
      $output['eventGen'] = Artisan::call('event:generate');
      print("generated events\n");
      // $output['dbSeed'] = Artisan::call('db:seed');
      // print("seeded db\n");
      // $output['MigrateDB'] = Artisan::call('migrate');
      // print("migrated db\n");
      Log::debug($output);
      print_r($output);
    }
}
