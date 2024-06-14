<?php

namespace App\Console\Commands;

use App\Models\GameModel;
use Carbon\Carbon;
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

        // Adding one day to the game dates
        $this->addDaysInGame();

        // Export the updated database and replace the old db file
        $this->exportDatabase();
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
    public function addDaysInGame()
    {
        try {
            $games = GameModel::get();
            foreach ($games as $game) {
                $game->gamedate = Carbon::parse($game->gamedate)->addDay();
                $game->gamedate_toDisplay = Carbon::parse($game->gamedate_toDisplay)->addDay();
                $game->save();
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
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
    private function exportDatabase()
    {
        $path = storage_path('app/demodb/umpire_demo.sql');
        $cnfPath = storage_path('app/demodb/.my.cnf');
        $database = env('DB_DATABASE');

        $exportCommand = sprintf(
            'mysqldump --defaults-file=%s --single-transaction --skip-lock-tables --skip-add-locks --skip-disable-keys --no-tablespaces %s > %s',
            escapeshellarg($cnfPath),
            escapeshellarg($database),
            escapeshellarg($path)
        );

        $output = [];
        $resultCode = null;
        exec($exportCommand . ' 2>&1', $output, $resultCode);

        if ($resultCode === 0) {
            $this->info('Database exported and replaced successfully!');
        } else {
            $this->error('Error exporting database. Code: ' . $resultCode);
            $this->error('Command Output: ' . implode("\n", $output));
        }
    }
}
