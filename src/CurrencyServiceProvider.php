<?php

namespace Indianic\CurrencyManagement;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Indianic\CurrencyManagement\Console\CurrencyManagementCommand;


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
        if ($this->app->runningInConsole()) {

            tap(new Filesystem(), function ($filesystem) {

                foreach (['Currency'] as $modelName) {
                    $filesystem->copy(__DIR__ .'/../stubs/Models/' . $modelName . '.stub', app_path('Models/' . $modelName . '.php'));
                }

                $filesystem->copy(__DIR__ .'/../stubs/seeders/CurrencyProvider.stub', database_path('seeders/CurrencyProvider.php'));

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
}
