<?php

/*
 * NOTICE OF LICENSE
 *
 * Part of the Rinvex Sparse Package.
 *
 * This source file is subject to The MIT License (MIT)
 * that is bundled with this package in the LICENSE file.
 *
 * Package: Rinvex Sparse Package
 * License: The MIT License (MIT)
 * Link:    https://rinvex.com
 */

declare(strict_types=1);

namespace Rinvex\Sparse\Providers;

use Illuminate\Support\ServiceProvider;

class SparseServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'rinvex.sparse');
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // Publish config
        $this->publishes([
            realpath(__DIR__.'/../../config/config.php') => config_path('rinvex.sparse.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            // Load migrations
            $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

            // Publish migrations
            $this->publishes([
                realpath(__DIR__.'/../database/migrations') => database_path('migrations'),
            ], 'migrations');
        }
    }
}
