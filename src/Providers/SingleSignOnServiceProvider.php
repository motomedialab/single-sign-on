<?php

namespace Motomedialab\SingleSignOn\Providers;

use Illuminate\Support\ServiceProvider;
use Motomedialab\SingleSignOn\Contracts\LogsInUser;

class SingleSignOnServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $baseDir = __DIR__ . '/../../';

        // load our routes.
        $this->loadRoutesFrom($baseDir . 'routes/sso.php');

        // create our configuration.
        $this->mergeConfigFrom($baseDir . 'config/sso.php', 'sso');

        $this->publishableFiles($baseDir);

        // setup any actions.
        $this->bindActions();
    }

    protected function publishableFiles(string $baseDir): void
    {
        $this->publishes([
            $baseDir . 'config/sso.php' => $this->app->configPath('sso.php'),
        ], 'sso-config');

        $this->publishes([
            $baseDir . 'migrations' => $this->app->databasePath('migrations'),
        ], 'sso-migrations');
    }

    protected function bindActions(): void
    {
        app()->bind(LogsInUser::class, config('sso.actions.login'));
    }
}
