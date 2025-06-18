<?php

namespace App\Imports;

use App\Dataset;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class DatasetImport implements OnEachRow, WithHeadingRow
{
public $headers = [];

    public function onRow(Row $row)
    {
        // No need to process each row if only header needed
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }
}
