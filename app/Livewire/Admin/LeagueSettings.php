<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\LeagueModel;

class LeagueSettings extends Component
{
    public function render()
    {
        $page_data = LeagueModel::get();
        $data = compact('page_data');
        return view('livewire.admin.league-settings', $data);
    }
}
