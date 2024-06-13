<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:resetdb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reseting db to a certain checkpoint';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    // Dropping all tables in the current database
    $this->dropAllTables();

    // Importing the SQL file
    $this->importSqlFile();

    $this->info('Database has been reset successfully.');
    }
    private function dropAllTables()
    {
        $tables = DB::select('SHOW TABLES');

        foreach ($tables as $table) {
            $tableArray = get_object_vars($table);
            $tableName = array_values($tableArray)[0];
            DB::statement('DROP TABLE IF EXISTS ' . $tableName);
        }

        $this->info('All tables dropped.');
    }

    private function importSqlFile()
    {
        $path = storage_path('app/demodb/umpire_demo.sql');

        if (!file_exists($path)) {
            $this->error("File not found: $path");
            return;
        }

        try {
            DB::unprepared(file_get_contents($path));
            $this->info('SQL file imported successfully!');
        } catch (\Exception $e) {
            $this->error('Error importing SQL file: ' . $e->getMessage());
        }
    }
}
