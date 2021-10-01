<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarInfo extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'school_id',
        'course_id',
        'year',
        'semester',
        'mother_name',
        'mother_birthday',
        'mother_occupation',
        'mother_living',
        'mother_educational_attainment',
        'father_name',
        'father_birthday',
        'father_occupation',
        'father_living',
        'father_educational_attainment',
    ];

    
    public function school()
    {
        return $this->belongsTo(ScholarSchool::class, 'school_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo(ScholarCourse::class, 'course_id', 'id');
    }
}
