<?php

namespace App\Jobs;

use App\Models\GameModel;
use App\Mail\ScheduleGame;
use App\Models\LeagueModel;
use App\Models\UmpireModel;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use App\Models\LeagueUmpireModel;
use App\Models\RefundPointsModel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Session;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Controllers\GeneralController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class AutoGameSchedule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $genController = new GeneralController();
            $genController->game_auto_schedule();
        } catch (\Throwable $th) {
            //throw $th;
            put_log_msg($th);
        }
    }

}
