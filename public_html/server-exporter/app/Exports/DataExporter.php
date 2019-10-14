<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class DataExporter implements FromArray
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return [[3],[5,6,7]];
    }
}
