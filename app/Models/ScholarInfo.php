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
        'mother_name',
        'mother_birthday',
        'mother_address',
        'mother_occupation',
        'mother_living',
        'mother_educational_attainment',
        'father_name',
        'father_birthday',
        'father_address',
        'father_occupation',
        'father_living',
        'father_educational_attainment',
    ];
}
