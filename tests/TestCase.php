<?php

declare(strict_types=1);

namespace FrittenKeeZ\Vouchers\Tests;

use FrittenKeeZ\Vouchers\Facades\Vouchers;
use FrittenKeeZ\Vouchers\Tests\Models\Color;
use FrittenKeeZ\Vouchers\VouchersServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        Relation::morphMap([
            'Color' => Color::class,
            'User' => \FrittenKeeZ\Vouchers\Tests\Models\User::class,
        ]);
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Vouchers' => Vouchers::class,
        ];
    }

    protected function getPackageProviders($app): array
    {
        return [
            VouchersServiceProvider::class,
        ];
    }
}
