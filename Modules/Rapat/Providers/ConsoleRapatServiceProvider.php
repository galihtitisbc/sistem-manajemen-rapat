<?php

namespace Modules\Rapat\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Rapat\Console\MakeLivewireComponentInModule;
use Modules\Rapat\Console\MigrationRapat;

class ConsoleRapatServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    protected $commands = [
        MigrationRapat::class,
        MakeLivewireComponentInModule::class
    ];
    public function register()
    {
        $this->commands($this->commands);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
