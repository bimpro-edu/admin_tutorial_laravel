<?php

namespace ThaoHR\Plugins;

use Illuminate\Support\ServiceProvider;
use ThaoHR\Plugins\Console\Commands\GeneratePluginCommand;
use ThaoHR\Plugins\Console\Commands\RemovePluginCommand;

class PluginsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GeneratePluginCommand::class,
                RemovePluginCommand::class,
            ]);
        }
    }
}
