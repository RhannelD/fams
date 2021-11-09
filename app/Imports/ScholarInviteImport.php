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
            $data = [];
            $data['email']    = trim($row[0]);
            if ( isset($row[1]) ) {
                $data['category'] = trim($row[1]);
            }

            $this->data[] = $data;
        }
    }

    public function getArray(): array
    {
        return $this->data;
    }
}
