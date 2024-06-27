<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LeagueModel;
use App\Models\ToggleSettings;
use Illuminate\Support\Facades\Session;

class LeagueSettings extends Component
{
    public $leagueRow;
    public $toggle = [];
    public function mount()
    {
        $this->toggle = [
            'age' => true,
            'divisions' => true,
            'auto_scheduler' => true,
            'teams' => true,
            'umpire_2' => true,
            'umpire_3' => true,
            'umpire_4' => true,
        ];
        if ($toggles = ToggleSettings::where('toggled_for', $this->leagueRow->leagueid)->get()) {
            foreach ($toggles as $toggleRow) {
                $this->toggle[$toggleRow->setting] = false;
            }
        }
    }
    public function render()
    {
        return view('livewire.league-settings');
    }
    public function applySettings()
    {
        $leagueRow =  $this->leagueRow;
        $toggle = $this->toggle;

        if (!empty($toggle)) {
            foreach ($toggle as $key => $val) {
                toggleSettings($leagueRow->leagueid, $key, !$val, $leagueRow->leagueid);
            }
        }
        Session::flash('message', 'Success');
        return redirect('league/settings/features');
    }
    public function updatedToggle()
    {
        if ($this->toggle['umpire_2'] == false) {
            $this->toggle['umpire_3'] = false;
            $this->toggle['umpire_4'] = false;
        } else if ($this->toggle['umpire_3'] == false) {
            $this->toggle['umpire_4'] = false;
        }
    }
}
