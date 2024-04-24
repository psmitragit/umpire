<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class GameSheet implements WithMultipleSheets
{
    private $team_data;
    private $location_data;
    private $demo_data;

    public function __construct($team_data, $location_data, $demo_data)
    {
        $this->team_data = $team_data;
        $this->location_data = $location_data;
        $this->demo_data = $demo_data;

    }

    public function sheets(): array
    {
        $sheets = [
            new AllSheets($this->demo_data, 'Game Import Format'),
            new AllSheets($this->team_data, 'Team Details'),
            new AllSheets($this->location_data, 'Location Details'),
        ];

        return $sheets;
    }
}
