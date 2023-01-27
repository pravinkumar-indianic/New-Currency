<?php

namespace Indianic\CurrencyManagement;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Indianic\CurrencyManagement\Console\CurrencyManagementCommand;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Indianic\CurrencyManagement\Nova\Resources\Currency;
use Indianic\CurrencyManagement\Policies\CurrencyManagementPolicy;

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

            tap(new Filesystem(), function ($filesystem) {

                foreach (['Currency'] as $modelName) {
                    $filesystem->copy(__DIR__ .'/../stubs/Models/' . $modelName . '.stub', app_path('Models/' . $modelName . '.php'));
                }

                $filesystem->copy(__DIR__ .'/../stubs/seeders/CurrenciesTableSeeder.stub', database_path('seeders/CurrenciesTableSeeder.php'));

                $filesystem->copy(__DIR__ .'/../stubs/migrations/2023_01_18_095958_currencies.stub', database_path('migrations/2023_01_18_095958_currencies.php'));

                File::isDirectory(app_path('Providers/DataProviders')) or File::makeDirectory(app_path('Providers/DataProviders'), 0777, true, true);

                $filesystem->copy(__DIR__ .'/../stubs/DataProviders/CurrencyProvider.stub', app_path('Providers/DataProviders/CurrencyProvider.php'));

            });

            $this->commands([
                CurrencyManagementCommand::class,
            ]);
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
