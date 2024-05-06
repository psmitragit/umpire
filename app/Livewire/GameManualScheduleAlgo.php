<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\GameModel;
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
    public function setUmpire($umpId)
    {
        $gameId = $this->tmpGameId;
        $pos = $this->tmpGamePos;
        $this->assignedGameUmpires[$gameId][$pos] = $umpId;
        $this->dispatch('hide-modal', modal: '#umpireModal');
    }
    public function render()
    {
        return view('livewire.game-manual-schedule-algo');
    }
}
