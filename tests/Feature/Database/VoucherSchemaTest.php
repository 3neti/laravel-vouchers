<?php

declare(strict_types=1);

use FrittenKeeZ\Vouchers\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

it('has the expected voucher schema extensions', function () {
    $vouchersTable = Config::table('vouchers');
    $redeemersTable = Config::table('redeemers');

    expect(Schema::hasTable($vouchersTable))->toBeTrue()
        ->and(Schema::hasTable($redeemersTable))->toBeTrue();

    expect(Schema::hasColumns($vouchersTable, [
        'processed_on',
        'idempotency_key',
        'idempotency_created_at',
        'voucher_type',
        'state',
        'rules',
        'target_amount',
        'locked_at',
        'closed_at',
        'metadata',
    ]))->toBeTrue();

    expect(Schema::hasColumn($redeemersTable, 'metadata'))->toBeTrue();
});

it('has the expected voucher indexes', function () {
    $table = Config::table('vouchers');

    $indexes = collect(getTableIndexes($table))->pluck('name')->values()->all();

    expect($indexes)->toContain('vouchers_idempotency_key_index')
        ->and($indexes)->toContain('vouchers_voucher_type_index')
        ->and($indexes)->toContain('vouchers_state_index');
});

/**
 * Get indexes for the current database driver.
 *
 * @return array<int, array{name:string, columns:array<int,string>, unique:bool}>
 */
function getTableIndexes(string $table): array
{
    $driver = DB::getDriverName();

    return match ($driver) {
        'sqlite' => getSqliteIndexes($table),
        'mysql' => getMySqlIndexes($table),
        default => throw new RuntimeException("Unsupported driver [{$driver}] for index assertion."),
    };
}

/**
 * @return array<int, array{name:string, columns:array<int,string>, unique:bool}>
 */
function getSqliteIndexes(string $table): array
{
    $rawIndexes = DB::select("PRAGMA index_list('{$table}')");

    return collect($rawIndexes)->map(function ($index) {
        $columns = DB::select("PRAGMA index_info('{$index->name}')");

        return [
            'name' => $index->name,
            'columns' => collect($columns)->pluck('name')->all(),
            'unique' => (bool) $index->unique,
        ];
    })->values()->all();
}

/**
 * @return array<int, array{name:string, columns:array<int,string>, unique:bool}>
 */
function getMySqlIndexes(string $table): array
{
    $database = DB::getDatabaseName();

    $rows = DB::table('information_schema.statistics')
        ->select('index_name as name', 'column_name', 'non_unique')
        ->where('table_schema', $database)
        ->where('table_name', $table)
        ->orderBy('index_name')
        ->orderBy('seq_in_index')
        ->get();

    return $rows
        ->groupBy('name')
        ->map(fn ($group, $name) => [
            'name' => $name,
            'columns' => $group->pluck('column_name')->all(),
            'unique' => ! (bool) $group->first()->non_unique,
        ])
        ->values()
        ->all();
}
