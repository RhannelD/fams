<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipScholar extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'category_id',
    ];
    
    public function category()
    {
        return $this->belongsTo(ScholarshipCategory::class, 'category_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function scopeWhereScholarshipId($query, $scholarship_id)
    {
        return $query->whereHas('category', function ($query) use ($scholarship_id) {
                $query->where('scholarship_id', $scholarship_id);
            });
    }
}
