<?php

namespace App\Traits;

use Carbon\Carbon;

trait YearSemTrait {
    public function get_acad_sem_month(Type $var = null)
    {
        return [
            1 => 'Jul 1',
            2 => 'Jan 3',
        ];
    }

    public function get_year_sem(Carbon $date = null) {
        if ( is_null($date) ) {
            $date = Carbon::now();
        }

        $acad_year = $this->get_acad_year($date);
        $acad_sem  = '2nd Sem';

        if ( $this->is_first_sem($date) ) {
            $acad_sem  = '1st Sem';
        }

        return "{$acad_year}-".($acad_year+1)." {$acad_sem}";
    }

    public function get_acad_year(Carbon $date = null)
    {
        if ( is_null($date) ) {
            $date = Carbon::now();
        }

        $year = $date->format('Y');

        $sem_first = Carbon::parse($this->get_acad_sem_month()[1])->format('m-d');

        return $sem_first > $date->format('m-d')? --$year: $year;
    }

    public function get_acad_sem(Carbon $date = null)
    {
        if ( is_null($date) ) {
            $date = Carbon::now();
        }

        $sem_first  = Carbon::parse($this->get_acad_sem_month()[1])->format('m-d');
        $sem_second = Carbon::parse($this->get_acad_sem_month()[2])->format('m-d');

        return $sem_first > $date->format('m-d')? ($sem_second > $date->format('m-d')? '1': '2'): '1';
    }

    public function is_first_sem(Carbon $date = null)
    {
        return $this->get_acad_sem($date) == '1';
    }
}
