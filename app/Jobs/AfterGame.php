<?php

namespace App\Jobs;

use App\Models\GameModel;
use App\Models\UmpireModel;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class AfterGame implements ShouldQueue
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
            $this->afterGame();
        } catch (\Throwable $th) {
            //throw $th;
            put_log_msg($th);
        }
    }
    public function afterGame()
    {
        // Log the output with a timestamp
        $logMessage = 'This cron(afterGame) runs at ' . now();
        put_log_msg($logMessage);
        // Log the output with a timestamp

        $past_games = GameModel::where('gamedate', '<', now())
            ->where('status', 0)
            ->get();
        if (!$past_games->isEmpty()) {
            foreach ($past_games as $past_game) {
                $past_game->report == 0 ? $flag = true : $flag = false;
                $reportNAN = 0;
                for ($i = 1; $i <= 4; $i++) {
                    $col = 'ump' . $i;
                    $reportCol = 'report' . $i;
                    $umpid = $past_game->{$col};
                    $owed = 0;
                    if ($umpid !== null) {
                        $umpire = UmpireModel::findOrFail($umpid);
                        if (!$flag) {
                            if ($past_game->{$reportCol} !== null) {
                                $umpFlag = true;
                            } else {
                                $umpFlag = false;
                                $reportNAN++;
                            }
                        } else {
                            $umpFlag = true;
                        }
                        if ($umpFlag) { //if report is given or no report needed thn only proceed to payment
                            $leagueumpire = $umpire->leagues()->where('leagueid', $past_game->leagueid)->first();
                            refund_point_to_Aumpire($leagueumpire, $past_game->gameid);
                            $owed = $leagueumpire->owed ?? 0;
                            if ($col == 'ump1') {
                                $pay = $past_game->ump1pay + $past_game->ump1bonus;
                            } else {
                                $pay = $past_game->ump234pay + $past_game->ump234bonus;
                            }
                            $profilePay = $leagueumpire->payout ?? 0;
                            //if profile pay is higher thn the game payout thn give umpire the  highest pay
                            if ($profilePay > $pay) {
                                $pay = $profilePay;
                            }
                            $owed += $pay;

                            //saving the owe
                            $leagueumpire->owed = $owed;
                            if ($leagueumpire->save()) {
                                add_payRecord($leagueumpire->leagueid, $leagueumpire->umpid, date('Y-m-d'), $pay, 'game', $past_game->gameid);
                            }
                        }
                    }
                }

                if ($reportNAN == 0) {
                    $past_game->status = 1;
                    $past_game->save();
                }
            }
        }
    }
}
