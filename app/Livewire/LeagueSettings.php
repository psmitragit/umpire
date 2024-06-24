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
            'age' => false,
            'divisions' => false,
            'auto_scheduler' => false,
            'teams' => false,
            'umpire_4' => false,
            'umpire_3' => false,
            'umpire_2' => false,
        ];
        if ($toggles = ToggleSettings::where('toggled_for', $this->leagueRow->leagueid)->get()) {
            foreach ($toggles as $toggleRow) {
                $this->toggle[$toggleRow->setting] = true;
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

        if ($this->toggle['umpire_2'] == true) {
            $this->toggle['umpire_3'] = true;
            $this->toggle['umpire_4'] = true;
        } else if ($this->toggle['umpire_3'] == true) {
            $this->toggle['umpire_4'] = true;
        }

        $toggle = $this->toggle;

        if (!empty($toggle)) {
            foreach ($toggle as $key => $val) {
                toggleSettings($leagueRow->leagueid, $key, $val, $leagueRow->leagueid);
            }
        }
        Session::flash('message', 'Success');
        return redirect('league/settings/features');
    }
    public function updatedToggle()
    {
        if ($this->toggle['umpire_2'] == true) {
            $this->toggle['umpire_3'] = true;
            $this->toggle['umpire_4'] = true;
        } else if ($this->toggle['umpire_3'] == true) {
            $this->toggle['umpire_4'] = true;
        }
    }
}
