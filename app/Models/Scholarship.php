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
        'link',
    ];

    public function scholars_count()
    {
        return ScholarshipScholar::whereHas('category', function ($query) {
                $query->where('scholarship_id', $this->id);
            })->count();
    }
    
    public function categories()
    {
        return $this->hasMany(ScholarshipCategory::class, 'scholarship_id', 'id');
    } 
    
    public function requirements()
    {
        return $this->hasMany(ScholarshipRequirement::class, 'scholarship_id', 'id');
    } 

    public function scopeWhereHasScholar($query, $user_id)
    {
        return $query->whereHas('categories', function ($query) use ($user_id) {
                $query->whereHas('scholars', function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                });
            });
    }
    
    public function get_num_of_scholars()
    {
        return ScholarshipScholar::whereScholarshipId($this->id)->count();
    }

    public function get_num_of_pending_responses()
    {
        $scholarship_id = $this->id;
        return ScholarResponse::whereNull('approval')
            ->whereHas('requirement', function ($query) use ($scholarship_id) {
                $query->where('scholarship_id', $scholarship_id);
            })->count();
    }

    public function get_num_of_pending_application_responses()
    {
        $scholarship_id = $this->id;
        return ScholarResponse::whereNull('approval')
            ->whereHas('requirement', function ($query) use ($scholarship_id) {
                $query->where('scholarship_id', $scholarship_id)
                ->where('promote', true);
            })->count();
    }

    public function get_num_of_pending_renewal_responses()
    {
        $scholarship_id = $this->id;
        return ScholarResponse::whereNull('approval')
            ->whereHas('requirement', function ($query) use ($scholarship_id) {
                $query->where('scholarship_id', $scholarship_id)
                ->where('promote', false);
            })->count();
    }
}
