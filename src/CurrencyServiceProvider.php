<?php

namespace Indianic\CurrencyManagement;

use Laravel\Nova\Nova;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use Indianic\CurrencyManagement\Nova\Resources\Currency;
use Indianic\CurrencyManagement\Policies\CurrencyManagementPolicy;

/**
 * Class CurrencyServiceProvider
 *
 * @package Indianic\CurrencyManagement
 */
class CurrencyServiceProvider extends ServiceProvider {

    public function boot() {
//         $this->setModulePermissions();
//        Gate::policy(\Indianic\CurrencyManagement\Models\Currency::class, CurrencyManagementPolicy::class);

        Nova::serving(function (ServingNova $event) {
            Nova::resources([
                Currency::class,
            ]);
        });

        if (!Schema::hasTable('currencies')) {
            if ($this->app->runningInConsole()) {
                $this->loadMigrationsFrom(base_path('vendor/indianic/currency-management-new/src/Database/migrations'));
                $path = 'vendor/indianic/currency-management-new/src/Database';
                $migrationPath = $path . "/migrations";
                if (is_dir($migrationPath)) {
                    foreach (array_diff(scandir($migrationPath, SCANDIR_SORT_NONE), [".", ".."]) as $migration) {
                        Artisan::call('migrate', [
                            '--path' => $migrationPath . "/" . $migration
                        ]);
                    }
                }
            }
        }


        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Register the application services.
     */
    public function register() {
        //
    }

    /**
     * Set Currency Managements module permissions
     *
     * @return void
     */
    private function setModulePermissions() {
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
