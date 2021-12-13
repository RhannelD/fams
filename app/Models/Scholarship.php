<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\YearSemTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scholarship extends Model
{
    use HasFactory, YearSemTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'scholarship',
        'link',
    ];

    public function scholars_count($acad_year, $acad_sem)
    {
        return ScholarshipScholar::whereHas('category', function ($query) {
                $query->where('scholarship_id', $this->id);
            })
            ->where('acad_year', $acad_year)
            ->where('acad_sem', $acad_sem)
            ->count();
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
    
    public function get_num_of_scholars(Carbon $date = null)
    {
        if ( is_null($date) ) {
            $date = Carbon::now();
        }

        return ScholarshipScholar::whereScholarshipId($this->id)->whereYearSem($this->get_acad_year($date), $this->get_acad_sem($date))->count();
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
