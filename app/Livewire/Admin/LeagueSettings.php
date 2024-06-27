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
        $this->resetToggle();
    }
    public function resetToggle()
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
    }
    public function render()
    {
        $page_data = LeagueModel::get();
        $data = compact('page_data');
        return view('livewire.admin.league-settings', $data);
    }
    public function manageSettings($leagueId)
    {
        $this->resetToggle();

        $this->leagueRow = LeagueModel::find($leagueId);
        if ($toggles = ToggleSettings::where('toggled_for', $leagueId)->get()) {
            foreach ($toggles as $toggleRow) {
                $this->toggle[$toggleRow->setting] = false;
            }
        }
        $this->dispatch('show-modal', modal: '#settingsModal');
    }
    public function updatedToggle()
    {
        $leagueRow =  $this->leagueRow;

        if ($this->toggle['umpire_2'] == false) {
            $this->toggle['umpire_3'] = false;
            $this->toggle['umpire_4'] = false;
        } else if ($this->toggle['umpire_3'] == false) {
            $this->toggle['umpire_4'] = false;
        }

        $toggle = $this->toggle;

        if (!empty($toggle)) {
            foreach ($toggle as $key => $val) {
                toggleSettings($leagueRow->leagueid, $key, !$val);
            }
        }
        $this->dispatch('success', msg: 'Success');
    }
}
