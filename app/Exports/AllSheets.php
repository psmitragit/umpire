<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;



class AllSheets implements FromView, WithHeadings, WithTitle, WithColumnWidths
{
    private $data;
    private $title;

    public function __construct($data, $title)
    {
        $this->data = $data;
        $this->title = $title;
    }

    public function view(): View
    {
        $headings = $this->headings();
        return view('exports.sheet_template', ['data' => $this->data, 'headings' => $headings]);
    }

    public function headings(): array
    {
        if ($this->title !== 'Game Import Format') {
            return ['id', 'Name'];
        } else {
            return ['gamedatetime', 'hometeamid', 'awayteamid', 'locationid', 'playersage', 'noofumpires', 'report', 'primaryumpirepayment', 'primaryumpirebonus', 'secondaryumpirepayment', 'secondaryumpirebonus'];
        }
    }
    public function title(): string
    {
        return $this->title;
    }
    public function columnWidths(): array
    {
        if ($this->title !== 'Game Import Format') {
            return [
                'A' => 10,
                'B' => 50,
            ];
        } else {
            return [
                'A' => 20,
                'B' => 15,
                'C' => 15,
                'D' => 15,
                'E' => 15,
                'F' => 15,
                'G' => 15,
                'H' => 15,
                'I' => 25,
                'J' => 25,
                'K' => 25,
            ];
        }
    }
}
