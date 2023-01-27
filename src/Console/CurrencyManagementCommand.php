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

        $this->call('migrate', [
            '--path' => 'database/migrations/2023_01_18_095958_currencies.php'
        ]);

        $this->call('db:seed', [
            '--class' => CurrenciesTableSeeder::class
        ]);

        $this->info('Publishing Configuration Successfully Completed.');
    }
}
