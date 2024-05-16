<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\GameModel;
use App\Mail\ScheduleGame;
use App\Models\LeagueModel;
use App\Models\UmpireModel;
use Illuminate\Support\Carbon;
use App\Models\LeagueUmpireModel;
use App\Models\RefundPointsModel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\GeneralController;

class GameManualScheduleAlgo extends Component
{
    public $algoGameDate;
    public $minGameDate;
    public $league_data;
    public $page_data;
    public $assignedGameUmpires;
    public $preAssignedGameUmpires;
    public $tmpGameId;
    public $tmpGamePos;
    public function mount()
    {
        $this->league_data = logged_in_league_data();
        $league_data = $this->league_data;
        $this->minGameDate = Carbon::now();
    }
    public function searchGames()
    {
        $league_data = $this->league_data;
        $targetDate = Carbon::parse($this->algoGameDate);
        if ($targetDate && $targetDate->startOfDay()->gte($this->minGameDate->startOfDay())) {
            $gameRows = [];
            $genController = new GeneralController();
            $result =  $genController->game_auto_schedule($league_data->leagueid, $targetDate, false);
            $assignedGameIds = @$result['game_ids'];
            $preAssignedGameUmpires = @$result['umpireColumnsThatWereAssignedPreviously'];
            $assignedGameUmpires = array();
            if (!empty($assignedGameIds)) {
                foreach ($assignedGameIds as $gameId) {
                    $gameRow = GameModel::where('gameid', $gameId)->first();
                    if ($gameRow) {
                        for ($i = 1; $i <= 4; $i++) {
                            $col = "ump$i";
                            if ($gameRow->{$col} !== null) {
                                $umpid = $gameRow->{$col};

                                $assignedGameUmpires[$gameRow->gameid][$col] = $umpid;

                                if (!isset($preAssignedGameUmpires[$gameRow->gameid][$col])) {
                                    //removeing umpire

                                    $remove_game_updated_data = [
                                        $col => null
                                    ];

                                    if ($gameRow->update($remove_game_updated_data)) {
                                        $leagueUmpireRow = LeagueUmpireModel::where('umpid', $umpid)
                                            ->where('leagueid', $league_data->leagueid)->first();

                                        //refunding the points that were cut during the auto assigning
                                        refund_point_to_Aumpire($leagueUmpireRow, $gameId);
                                    }

                                    //removeing umpire
                                }
                            }
                        }
                        $gameRows[] = $gameRow;
                    }
                }
                $page_data = $gameRows;
                $this->page_data = $page_data;
                $this->assignedGameUmpires = $assignedGameUmpires;
                $this->preAssignedGameUmpires = $preAssignedGameUmpires;
            } else {
                $this->dispatch('error', msg: "No games found.");
            }
        } else {
            $errMsg = "Please select a date onwards " . $this->minGameDate->format('m/d/Y');
            $this->dispatch('error', msg: $errMsg);
        }
    }
    public function assignRemoveUmpire($gameId, $pos)
    {
        $this->tmpGameId = $gameId;
        $this->tmpGamePos = $pos;
        $gameRow = GameModel::find($gameId);

        $posNo = (int)substr($pos, -1);
        $umpreqd = (int)$gameRow->umpreqd;
        if ($posNo <= $umpreqd) {
            if (!isset($this->preAssignedGameUmpires[$gameId][$pos])) {
                if (isset($this->assignedGameUmpires[$gameId][$pos])) {
                    unset($this->assignedGameUmpires[$gameId][$pos]);
                } else {
                    $this->dispatch('show-modal', modal: '#umpireModal');
                }
            } else {
                $this->dispatch('error', msg: 'Can\'t remove previously assigned umpires.');
            }
        } else {
            $this->dispatch('error', msg: 'Can\'t assign umpire to this position.');
        }
    }
    public function setUmpire($umpid)
    {
        $gameid = $this->tmpGameId;
        $pos = $this->tmpGamePos;

        //running checks

        $umpire = UmpireModel::findOrFail($umpid);
        $game = GameModel::findOrFail($gameid);
        $league = $game->league;

        $league_umpire = $umpire->leagues()->where('leagueid', $game->leagueid)->first();
        if ($league_umpire->status !== 1) {
            $condition_met = false;
            $umpire_age = (int)get_age($umpire->dob);
            $mainumpage = (int)$league->mainumpage;
            $otherumpage = (int)$league->otherumpage;
            $game_date = explode(' ', $game->gamedate)[0];
            $game_time = substr(explode(' ', $game->gamedate)[1], 0, 5);
            $game_teams = array($game->hometeamid, $game->awayteamid);
            $game_divisionsRows = array($game->hometeam->division, $game->awayteam->division);

            // Checking umpire blocked dates and time
            //due to new changes consider *blocked_dates* as *available_dates*
            $blocked_dates = $umpire->blocked_dates;

            if (!$blocked_dates->isEmpty()) {
                foreach ($blocked_dates as $blocked_date) {
                    if ($blocked_date->blockdate == $game_date) {
                        if ($blocked_date->blocktime !== '') {
                            $blocked_timeArray = explode(',', $blocked_date->blocktime);
                            if (in_array($game_time, $blocked_timeArray)) {
                                $condition_met = false;
                                break; // Exit this loop
                            } else {
                                $condition_met = true; // Set the flag to true if time is blocked
                            }
                        } else {
                            $condition_met = false; // Set the flag to true if date is blocked
                            break; // Exit this loop
                        }
                    } else {
                        $condition_met = true; // Set the flag to true if time is blocked
                    }
                }
            } else {
                $condition_met = true; // Set the flag to true if time is blocked
            }

            if ($condition_met) {
                $this->dispatch('error', msg: 'Umpire: ' . htmlspecialchars($umpire->name) . ' not available on the game time.');
            }
            //stack into a single msg to avoid the multiple errors

            // Checking umpire's blocked grounds
            $blocked_grounds = $umpire->blocked_ground;
            foreach ($blocked_grounds as $blocked_ground) {
                if ($blocked_ground->locid == $game->locid) {
                    $condition_met = true; // Set the flag to true if ground is blocked
                    $this->dispatch('error', msg: 'Umpire: ' . htmlspecialchars($umpire->name) . ' not available on : ' . htmlspecialchars($blocked_ground->ground->ground));
                    break; // Exit this loop
                }
            }

            // Checking umpire's blocked divisions
            $blocked_divisions = $umpire->blocked_division;
            if (!empty($game_divisionsRows)) {
                $game_divisions = [];
                foreach ($game_divisionsRows as $game_divisionsRow) {
                    if ($game_divisionsRow) {
                        $game_divisions[] = $game_divisionsRow->id;
                    }
                }
                foreach ($blocked_divisions as $blocked_division) {
                    if (in_array($blocked_division->divid, $game_divisions)) {
                        $condition_met = true; // Set the flag to true if team is blocked
                        $this->dispatch('error', msg: 'Umpire: ' . htmlspecialchars($umpire->name) . ' blocked from games which includes Division: ' . htmlspecialchars($blocked_division->division->name));
                        break; // Exit this loop
                    }
                }
            }

            // Checking umpire's blocked teams
            $blocked_teams = $umpire->blocked_team;
            foreach ($blocked_teams as $blocked_team) {
                if (in_array($blocked_team->teamid, $game_teams)) {
                    $condition_met = true; // Set the flag to true if team is blocked
                    $this->dispatch('error', msg: 'Umpire: ' . htmlspecialchars($umpire->name) . ' blocked from games which includes Team: ' . htmlspecialchars($blocked_team->team->teamname));
                    break; // Exit this loop
                }
            }
            //checking umpire age
            $game_player_age = (int)$game->playersage;
            $age_diff = $umpire_age - $game_player_age;
            if ($pos == 'ump1') {
                if ($age_diff < $mainumpage) {
                    $condition_met = true; // Set the flag to true if age diff is lower
                    $this->dispatch('error', msg: 'Umpire: ' . htmlspecialchars($umpire->name) . ' don\'t meet the game\'s age requirement.');
                }
            } else {
                if ($age_diff < $otherumpage) {
                    $condition_met = true; // Set the flag to true if age diff is lower
                    $this->dispatch('error', msg: 'Umpire: ' . htmlspecialchars($umpire->name) . ' don\'t meet the game\'s age requirement.');
                }
            }
            //check if have other games on the same datetime
            $samedategames = $this->assignedGameUmpires;

            if (!empty($samedategames)) {
                foreach ($samedategames as $samedategame) {
                    foreach ($samedategame as $samedategamepos) {
                        if ((int)$samedategamepos == (int)$umpid) {
                            $condition_met = true; // Set the flag to true if found another game on the same datetime
                            $this->dispatch('error', msg: 'Umpire: ' . htmlspecialchars($umpire->name) . ' already assigned to another game.');
                            break;
                        }
                    }
                }
            }


            if (!$condition_met) {
                $this->assignedGameUmpires[$gameid][$pos] = $umpid;
            }
        } else {
            $this->dispatch('error', msg: 'Umpire: ' . htmlspecialchars($umpire->name) . ' blocked from this league.');
        }

        //running checks

        $this->dispatch('hide-modal', modal: '#umpireModal');
    }
    public function saveSchedule()
    {
        try {
            $preAssignedGameUmpires = $this->preAssignedGameUmpires;
            $assignedGameUmpires = $this->assignedGameUmpires;
            $finalAssign = [];
            if (!empty($preAssignedGameUmpires)) {
                foreach ($preAssignedGameUmpires as $key => $values) {
                    foreach ($values as $subKey => $value) {
                        unset($assignedGameUmpires[$key][$subKey]);
                    }
                    if (empty($assignedGameUmpires[$key])) {
                        unset($assignedGameUmpires[$key]);
                    }
                }
            }
            $finalAssign = $assignedGameUmpires;
            // dd($preAssignedGameUmpires, $assignedGameUmpires);
            if (!empty($finalAssign)) {
                foreach ($finalAssign as $gameId => $umpPos) {
                    $game = GameModel::findOrFail($gameId);
                    if (!empty($umpPos)) {
                        foreach ($umpPos as $pos => $umpId) {
                            $game->{$pos} = $umpId;

                            //after umpassigned

                            $leagueUmpire = LeagueUmpireModel::where('leagueid', $game->leagueid)->where('umpid', $umpId)->first();
                            $addLessPointData = addSubPoint($gameId, $umpId, $pos);
                            $addLess = $addLessPointData[0];
                            $point = $addLessPointData[1];
                            $current_umpire_point = $leagueUmpire->points;
                            $updated_umpire_point_after_game_assigned = $current_umpire_point + ($addLess . $point);
                            //updating leagueumpire point
                            $updated_league_umpire_row_data = [
                                'points' => $updated_umpire_point_after_game_assigned
                            ];
                            if ($leagueUmpire->update($updated_league_umpire_row_data)) {
                                //storing points to a table to refund it after the game completion
                                $refund_point_data = [
                                    'leagueumpires_id' => $leagueUmpire->id,
                                    'game_id' => $gameId,
                                    'addless' => $addLess,
                                    'point' => $point,
                                ];
                                RefundPointsModel::create($refund_point_data);
                            }

                            $league = $game->league;
                            $assigned_umpire_row = UmpireModel::find($umpId);
                            try {
                                //notification mail
                                if ($assigned_umpire_row->email_settings->schedule_game == 1) {
                                    $umpire_email = $assigned_umpire_row->user->email;
                                    Mail::to($umpire_email)->send(new ScheduleGame($league, $assigned_umpire_row, $game, 'ump', $umpire_email));
                                }
                                if ($league->email_settings->join_game == 1) {
                                    foreach ($league->users as $league_admin) {
                                        $league_admin_email = $league_admin->email;
                                        Mail::to($league_admin_email)->send(new ScheduleGame($league, $assigned_umpire_row, $game, 'league', $league_admin_email));
                                    }
                                }
                                //notification mail end
                            } catch (\Throwable $th) {
                                // dd($th);
                            }
                            $msg = 'New game assigned on ' . date('D m/d/y', strtotime($game->gamedate));
                            $msg2 = $assigned_umpire_row->name . ' assigned to a game on ' . date('D m/d/y', strtotime($game->gamedate));
                            add_notification($assigned_umpire_row->umpid, $msg, 4, 'ump');
                            add_notification($game->leagueid, $msg2, 4, 'league');


                            //after umpassigned
                        }
                        $game->save();
                    }
                }
            }
            Session::flash('message', 'Success');
            return redirect('league/game-manual-schedule');
        } catch (\Throwable $th) {
            // dd($th);
            $this->dispatch('error', msg: 'Something went wrong.!!');
        }
    }
    public function render()
    {
        return view('livewire.game-manual-schedule-algo');
    }
}
