<?php

namespace Motomedialab\SingleSignOn\Providers;

use Illuminate\Support\ServiceProvider;
use Motomedialab\SingleSignOn\Contracts\LogsInUser;

class SingleSignOnServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $baseDir = __DIR__ . '/../../';
        $configFile = $baseDir . 'config/sso.php';

        // load our routes.
        $this->loadRoutesFrom($baseDir . 'routes/sso.php');

        // create our configuration.
        $this->mergeConfigFrom($configFile, 'sso');

        $this->publishes([
            $configFile => $this->app->configPath('sso.php'),
        ], 'sso-config');

        // setup any actions.
        $this->bindActions();
    }

    protected function bindActions(): void
    {
        app()->bind(LogsInUser::class, config('sso.actions.login'));
    }
}
