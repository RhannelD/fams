<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipRequirementCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'requirement_id',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(ScholarshipCategory::class, 'category_id', 'id');
    }

    public function count_previous_scholars_by_year_sem($acad_year, $acad_sem)
    {
        $prev_acad_year = $acad_sem=='1'? $acad_year-1: $acad_year;
        $prev_acad_sem  = $acad_sem=='1'? '2': '1';

        return ScholarshipScholar::where('category_id', $this->category_id)
            ->where('acad_year', $prev_acad_year)
            ->where('acad_sem', $prev_acad_sem)
            ->count();
    }
}
