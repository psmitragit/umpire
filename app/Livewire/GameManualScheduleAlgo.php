<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\GameModel;
use App\Models\UmpireModel;
use Illuminate\Support\Carbon;
use App\Models\LeagueUmpireModel;
use App\Http\Controllers\GeneralController;

class GameManualScheduleAlgo extends Component
{
    public $league_data;
    public $page_data;
    public $assignedGameUmpires;
    public $tmpGameId;
    public $tmpGamePos;
    public function mount()
    {
        $this->league_data = logged_in_league_data();
        $league_data = $this->league_data;
        $genController = new GeneralController();
        $assignedGameIds =  $genController->game_auto_schedule($league_data->leagueid, false, false);
        $assignedGameUmpires = array();
        if (!empty($assignedGameIds)) {
            foreach ($assignedGameIds as $gameId) {
                $gameRow = GameModel::find($gameId);
                for ($i = 1; $i <= 4; $i++) {
                    $col = "ump$i";
                    if ($gameRow->{$col} !== null) {
                        $umpid = $gameRow->{$col};
                        $assignedGameUmpires[$gameRow->gameid][$col] = $umpid;
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
                $gameRows[] = $gameRow;
            }
        }
        $page_data = $gameRows;
        $this->page_data = $page_data;
        $this->assignedGameUmpires = $assignedGameUmpires;
    }
    public function assignRemoveUmpire($gameId, $pos)
    {
        $this->tmpGameId = $gameId;
        $this->tmpGamePos = $pos;
        $gameRow = GameModel::find($gameId);

        $posNo = (int)substr($pos, -1);
        $umpreqd = (int)$gameRow->umpreqd;
        if ($posNo <= $umpreqd) {
            if (isset($this->assignedGameUmpires[$gameId][$pos])) {
                unset($this->assignedGameUmpires[$gameId][$pos]);
            } else {
                $this->dispatch('show-modal', modal: '#umpireModal');
            }
        } else {
            $this->dispatch('error', msg: 'Can\'t assign umpire to this position.');
        }
    }
    public function setUmpire($umpid)
    {
        $gameid = $this->tmpGameId;
        $pos = $this->tmpGamePos;

        //running check

        $umpire = UmpireModel::findOrFail($umpid);
        $game = GameModel::findOrFail($gameid);
        $league = $game->league;
        $res = [];

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
                                $this->dispatch('error', msg: 'Umpire: ' . htmlspecialchars($umpire->name) . ' not available on the game time.');
                            }
                        } else {
                            $condition_met = false; // Set the flag to true if date is blocked
                            break; // Exit this loop
                        }
                    } else {
                        $condition_met = true; // Set the flag to true if time is blocked
                        $this->dispatch('error', msg: 'Umpire: ' . htmlspecialchars($umpire->name) . ' not available on the game time.');
                    }
                }
            } else {
                $condition_met = true; // Set the flag to true if time is blocked
                $this->dispatch('error', msg: 'Umpire: ' . htmlspecialchars($umpire->name) . ' not available on the game time.');
            }

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
            $gapMorethnTwo = 0;
            $gamedatetime = $game->gamedate;
            $samedategames = GameModel::whereDate('gamedate', explode(' ', $gamedatetime)[0])
                ->where(function ($query) use ($umpid) {
                    $query->orWhere('ump1', $umpid)
                        ->orWhere('ump2', $umpid)
                        ->orWhere('ump3', $umpid)
                        ->orWhere('ump4', $umpid);
                })->get();

            if (!$samedategames->isEmpty()) {
                $gapMorethnTwo = 1;
                foreach ($samedategames as $samedategame) {
                    $samedategamedatetime = $samedategame->gamedate;
                    $gameDateTimeObj = Carbon::parse($gamedatetime);
                    $sameDateGameDateTimeObj = Carbon::parse($samedategamedatetime);

                    if ($gameDateTimeObj->diffInHours($sameDateGameDateTimeObj) < 2) {
                        $gapMorethnTwo = 2;
                        break;
                    }
                }

                $condition_met = true; // Set the flag to true if found another game on the same datetime
            }

            if (!$condition_met) {
                $this->assignedGameUmpires[$gameid][$pos] = $umpid;
            } else {
                if ($gapMorethnTwo == 1) {
                    $res = ['status' => 2, 'gameid' => $gameid, 'pos' => $pos, 'umpid' => $umpid]; //for same gamedate
                } elseif ($gapMorethnTwo == 2) {
                    $res = ['status' => 0];
                    $this->dispatch('error', msg: 'Difference between umpire\'s assigned games are less than 2 hours.');
                }
            }
        } else {
            $this->dispatch('error', msg: 'Umpire: ' . htmlspecialchars($umpire->name) . ' blocked from this league.');
            $res = ['status' => 0];
        }

        //running check

        $this->dispatch('hide-modal', modal: '#umpireModal');
    }
    public function render()
    {
        return view('livewire.game-manual-schedule-algo');
    }
}
