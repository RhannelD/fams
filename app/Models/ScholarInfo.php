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
        'course_id',
        'srcode',
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

    
    public function course()
    {
        return $this->belongsTo(ScholarCourse::class, 'course_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
