<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'scholarship',
    ];

    public function scholars_count()
    {
        return ScholarshipScholar::whereHas('category', function ($query) {
                $query->where('scholarship_id', $this->id);
            })->count();
    }
    
    public function officers()
    {
        return $this->hasMany(ScholarshipOfficer::class, 'scholarship_id', 'id');
    }
    
    public function categories()
    {
        return $this->hasMany(ScholarshipCategory::class, 'scholarship_id', 'id');
    } 

    
    public function scopeWhereHasScholar($query, $user_id)
    {
        return $query->whereHas('categories', function ($query) use ($user_id) {
                $query->whereHas('scholars', function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                });
            });
    }
    
    public function scopeWhereHasOfficer($query, $user_id)
    {
        return $query->whereHas('officers', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
    }
}
