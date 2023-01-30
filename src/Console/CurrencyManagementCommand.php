<?php

namespace Indianic\CurrencyManagement\Console;

use Illuminate\Console\Command;
use Database\Seeders\CurrenciesTableSeeder;

/**
 * Class CurrencyManagementCommand
 *
 * @package Indianic\CurrencyManagement\Console
 */
class CurrencyManagementCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:currency';

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
     */
    public function handle()
    {
        $this->info('Publishing Configuration...');
        $path = base_path('vendor/indianic/currency-management-new/database');
        $migrationPath = $path."/migrations";
        if (is_dir($migrationPath)) {
            foreach (array_diff(scandir($migrationPath, SCANDIR_SORT_NONE), [".",".."]) as $migration) {
                $this->call('migrate', [
                    '--path' => $migrationPath."/".$migration
                ]);
            }
        }

        if (is_dir($path . "/Seeders")) {
            $file_names = glob($path . "/Seeders" . '/*.php');
            foreach ($file_names as $filename) {
                $class = basename($filename, '.php');
                echo "\033[1;33mSeeding:\033[0m {$class}\n";
                $startTime = microtime(true);
                Artisan::call('db:seed', [ '--class' =>'Indianic\\CurrencyManagement\\Database\\Seeders\\'.$class, '--force' => '' ]);
                $runTime = round(microtime(true) - $startTime, 2);
                echo "\033[0;32mSeeded:\033[0m {$class} ({$runTime} seconds)\n";
            }
        }

        $this->info('Publishing Configuration Successfully Completed.');
    }
}
