<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class ScholarInviteImport implements ToArray
{
    private $data;

    public function __construct()
    {
        $this->data = [];
    }

    public function array(array $rows)
    {
        foreach ($rows as $row) {
            $this->data[] = array('email' => trim($row[0]), 'category' => trim($row[1]));
        }
    }

    public function getArray(): array
    {
        return $this->data;
    }
}
