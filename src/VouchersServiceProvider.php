<?php

declare(strict_types=1);

namespace FrittenKeeZ\Vouchers;

use Illuminate\Support\ServiceProvider;

class VouchersServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            $this->getPublishConfigPath() => config_path('vouchers.php'),
        ], 'config');

        $this->loadMigrationsFrom($this->getMigrationsPath());
    }

    public function register(): void
    {
        $this->mergeConfigFrom($this->getPublishConfigPath(), 'vouchers');

        $this->app->bind('vouchers', fn () => new Vouchers());
    }

    protected function getPublishConfigPath(): string
    {
        return __DIR__.'/../publishes/config/vouchers.php';
    }

    protected function getMigrationsPath(): string
    {
        return __DIR__.'/../database/migrations';
    }
}
