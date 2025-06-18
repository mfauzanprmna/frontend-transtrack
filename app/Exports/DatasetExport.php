<?php

namespace App\Exports;

use App\Dataset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DatasetExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Dataset::all()->pluck('data');
    }

    public function headings(): array
    {
        $first = Dataset::first();
        return $first ? array_keys($first->data) : [];
    }
}
