<?php

namespace Netsells\LaravelNovaTrixAttachments;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Str;
use Laravel\Nova\Trix\PruneStaleAttachments;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->app->resolving(Schedule::class, function (Schedule $schedule, Application $app) {
            if (! $this->hasMigrated($app['migration.repository'], 'create_trix_tables')) {
                return;
            }

            $schedule->call(new PruneStaleAttachments())
                ->daily()
                ->name('prune-stale-trix-attachments')
                ->onOneServer();
        });
    }

    private function hasMigrated(MigrationRepositoryInterface $repository, string $file): bool
    {
        return Collection::make($repository->getRan())->contains(function (string $migration) use ($file) {
            return Str::endsWith($migration, $file);
        });
    }
}
