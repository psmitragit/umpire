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
    protected $signature = 'run:resetgamedate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reseting gamedates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $games = GameModel::get();
            foreach ($games as $game) {

                $game->gamedate = Carbon::parse($game->gamedate)->addDay();
                $game->gamedate_toDisplay = Carbon::parse($game->gamedate_toDisplay)->addDay();

                $game->save();
                $this->info('Date reseted for gameid: ' . $game->gameid);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
