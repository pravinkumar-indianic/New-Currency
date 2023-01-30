<?php

namespace Indianic\CurrencyManagement;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Indianic\CurrencyManagement\Nova\Resources\Currency;
use Indianic\CurrencyManagement\Policies\CurrencyManagementPolicy;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

/**
 * Class CurrencyServiceProvider
 *
 * @package Indianic\CurrencyManagement
 */
class CurrencyServiceProvider extends ServiceProvider
{
    /** @var Command */
    protected $command;

    public function boot()
    {
//         $this->setModulePermissions();
//        Gate::policy(\Indianic\CurrencyManagement\Models\Currency::class, CurrencyManagementPolicy::class);

        Nova::serving(function (ServingNova $event) {
            Nova::resources([
                Currency::class,
            ]);
        });

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(base_path('vendor/indianic/currency-management-new/Database/migrations'));
            $path = 'vendor/indianic/currency-management-new/Database';
            $migrationPath = $path."/migrations";
            if (is_dir($migrationPath)) {
                foreach (array_diff(scandir($migrationPath, SCANDIR_SORT_NONE), [".",".."]) as $migration) {
                    Artisan::call('migrate', [
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
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }

        /**
     * Set Currency Managements module permissions
     *
     * @return void
     */
    private function setModulePermissions()
    {
        $existingPermissions = config('nova-permissions.permissions');

        $existingPermissions['view currency-management'] = [
            'display_name' => 'View currency management',
            'description' => 'Can view currency management',
            'group' => 'Currency Management'
        ];

        // $existingPermissions['create currency-management'] = [
        //     'display_name' => 'Create currency management',
        //     'description'  => 'Can create currency management',
        //     'group'        => 'Currency Management'
        // ];

        $existingPermissions['update currency-management'] = [
            'display_name' => 'Update currency management',
            'description' => 'Can update currency management',
            'group' => 'Currency Management'
        ];

        // $existingPermissions['delete currency-management'] = [
        //     'display_name' => 'Delete currency management',
        //     'description'  => 'Can delete currency management',
        //     'group'        => 'Currency Management'
        // ];

        \Config::set('nova-permissions.permissions', $existingPermissions);
    }
}
