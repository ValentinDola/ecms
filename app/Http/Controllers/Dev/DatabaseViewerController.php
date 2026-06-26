<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseViewerController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(app()->environment(['local', 'testing', 'development']), 404);

        if ($request->filled('open')) {
            $path = $request->input('open');
            $fullPath = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
            $targetPath = is_file($fullPath) ? dirname($fullPath) : $fullPath;

            if (is_dir($targetPath)) {
                $command = match (PHP_OS_FAMILY) {
                    'Windows' => 'explorer ' . escapeshellarg($targetPath),
                    'Darwin' => 'open ' . escapeshellarg($targetPath),
                    default => 'xdg-open ' . escapeshellarg($targetPath),
                };

                exec($command);
            }
        }

        $tables = $this->listTables();
        $selectedTable = $request->input('table');

        if ($selectedTable && ! in_array($selectedTable, $tables, true)) {
            $selectedTable = null;
        }

        if (! $selectedTable && $tables !== []) {
            $selectedTable = $tables[0];
        }

        $tableData = [];
        $columns = [];

        if ($selectedTable) {
            $tableData = DB::table($selectedTable)->limit(25)->get();
            $columns = Schema::getColumnListing($selectedTable);
        }

        $tableStats = collect($tables)->mapWithKeys(function (string $table) {
            return [$table => [
                'count' => DB::table($table)->count(),
                'columns' => Schema::getColumnListing($table),
            ]];
        })->all();

        $databasePath = $this->resolveDatabasePath();

        return view('dev.database-viewer', compact('tables', 'selectedTable', 'tableData', 'columns', 'tableStats', 'databasePath'));
    }

    private function resolveDatabasePath(): ?string
    {
        $connection = DB::connection();

        if ($connection->getDriverName() !== 'sqlite') {
            return null;
        }

        $database = config('database.connections.sqlite.database');

        if ($database === ':memory:') {
            return null;
        }

        return str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $database);
    }

    private function listTables(): array
    {
        $connection = DB::connection();

        $results = match ($connection->getDriverName()) {
            'sqlite' => $connection->select("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%' ORDER BY name"),
            'pgsql' => $connection->select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname != 'pg_catalog' AND schemaname != 'information_schema' ORDER BY tablename"),
            default => $connection->select('SHOW TABLES'),
        };

        return array_values(array_map(function ($result) {
            if (is_object($result)) {
                return (string) current((array) $result);
            }

            return (string) $result;
        }, $results));
    }
}
