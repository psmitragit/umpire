<?php

namespace App\Livewire\Admin;

use App\Models\ToggleSettings;
use Livewire\Component;
use App\Models\LeagueModel;

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
            'umpire_four' => false,
            'umpire_three' => false,
            'umpire_two' => false,
        ];
    }
    public function render()
    {
        $page_data = LeagueModel::get();
        $data = compact('page_data');
        return view('livewire.admin.league-settings', $data);
    }
    public function manageSettings($leagueId)
    {
        $this->leagueRow = LeagueModel::find($leagueId);
        if ($toggles = ToggleSettings::where('toggled_for', $leagueId)->get()) {
            foreach ($toggles as $toggleRow) {
                $this->toggle[$toggleRow->setting] = true;
            }
        }
        $this->dispatch('show-modal', modal: '#settingsModal');
    }
    public function updatedToggle()
    {
        $leagueRow =  $this->leagueRow;

        if ($this->toggle['umpire_two'] == true) {
            $this->toggle['umpire_three'] = true;
            $this->toggle['umpire_four'] = true;
        } else if ($this->toggle['umpire_three'] == true) {
            $this->toggle['umpire_four'] = true;
        }

        $toggle = $this->toggle;

        if (!empty($toggle)) {
            foreach ($toggle as $key => $val) {
                toggleSettings($leagueRow->leagueid, $key, $val);
            }
        }
        $this->dispatch('success', msg: 'Success');
    }
}
