<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class GameImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    protected $data = [];
    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {
            $this->data[] = $row->toArray();
        }

    }
    public function getData()
    {
        return $this->data;
    }
}
