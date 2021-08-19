<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ScholarshipRequirement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'scholarship_id',
        'requirement',
        'description',
        'promote',
        'enable',
        'start_at',
        'end_at',
    ];

    public function can_be_accessed()
    {
        $date_end = Carbon::parse($this->end_at);
        $date_now = Carbon::now()->toDateTimeString();

        if ( !isset($this->enable) ) {
            if ($date_end > $date_now) {
                return 'ongoing';
            }
            return 'disabled';
        }
        if ( !$this->enable ) {
            return 'disabled';
        }
        if ( $date_end > $date_now ) {
            return 'ongoing';
        }
        return 'finished';
    }

    public function has_started()
    {
        $date_start = Carbon::parse($this->start_at);
        $date_end = Carbon::parse($this->end_at);
        $date_now = Carbon::now()->toDateTimeString();
        
        if ( isset($this->enable) ) {
            return $this->enable;
        }
        return ( $date_now>$date_start && $date_now<$date_end );
    }

    public function categories()
    {
        return $this->hasMany(ScholarshipRequirementCategory::class, 'requirement_id', 'id');
    }

    public function responses()
    {
        return $this->hasMany(ScholarResponse::class, 'requirement_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(ScholarshipRequirementItem::class, 'requirement_id', 'id')->orderBy('position');
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'scholarship_id', 'id');
    }
}
