<?php

namespace CrankUpIT\Admin;

use Illuminate\Support\ServiceProvider;
use CrankUpIT\Admin\Console\InstallCommand;

class AdminServicePorvider extends ServiceProvider
{
    /**
     * Boot function of AdminServicePorvider
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
        // Files that go into the laravel app
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'admin');
    }

    public function register()
    {
        // Flises that stay in the package.
    }
}
