<?php

namespace FjAhmed\LaravelLogKeeper\Providers;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\ServiceProvider as Provider;
use FjAhmed\LaravelLogKeeper\Commands\LogKeeper;
use Symfony\Component\Finder\Finder;
use Illuminate\Filesystem\Filesystem;

class LaravelServiceProvider extends Provider
{
    public function boot(): void
    {
        $this->loadAutoloader(base_path('packages'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-log-keeper.php', 'laravel-log-keeper');

        $this->app->singleton('command.laravel-log-keeper', function ($app) {
            return $app[LogKeeper::class];
        });

        $this->commands('command.laravel-log-keeper');
    }

    /**
     * Require composer's autoload file the packages.
     *
     * @return void
     *
     * @throws FileNotFoundException
     */
    protected function loadAutoloader($path)
    {
        $finder = new Finder;
        $files  = new Filesystem;

        $autoLoads = $finder->in($path)->files()->name('autoload.php')->depth('<= 3')->followLinks();

        foreach ($autoLoads as $file) {
            $files->requireOnce($file->getRealPath());
        }
    }
}
