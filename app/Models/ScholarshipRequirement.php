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
        if ( isset($this->enable) )
            return $this->enable? 'ongoing': 'disabled';

        $date_start = Carbon::parse($this->start_at)->toDateTimeString();
        $date_end = Carbon::parse($this->end_at)->toDateTimeString();
        $date_now = Carbon::now()->toDateTimeString();

        return ($date_start<=$date_now && $date_now<=$date_end)?  'ongoing': 'disabled';
    }

    public function has_started()
    {
        if ( isset($this->enable) )
            return $this->enable;
        
        $date_start = Carbon::parse($this->start_at)->toDateTimeString();
        $date_end = Carbon::parse($this->end_at)->toDateTimeString();
        $date_now = Carbon::now()->toDateTimeString();
        
        return ( $date_now>=$date_start && $date_now<=$date_end );
    }

    public function get_submitted_responses_count()
    {
        return $this->responses->whereNotNull('submit_at')->count();
    }

    public function has_categories()
    {
        return ScholarshipRequirementCategory::where('requirement_id', $this->id)->exists();
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

    public function agreements()
    {
        return $this->hasMany(ScholarshipRequirementAgreement::class, 'requirement_id', 'id');
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'scholarship_id', 'id');
    }


    public function scopeWhereHasItemWithType($query, $type)
    {
        return $query->whereHas('items', function ($query) use ($type) {
                $query->where('type', $type);
            });
    }


    public function has_item_cor()
    {
        return ScholarshipRequirementItem::where('requirement_id', $this->id)->where('type', 'cor')->exists();
    }

    public function has_item_units()
    {
        return ScholarshipRequirementItem::where('requirement_id', $this->id)->where('type', 'units')->exists();
    }

    public function has_item_grade()
    {
        return ScholarshipRequirementItem::where('requirement_id', $this->id)->where('type', 'grade')->exists();
    }

    public function has_item_gwa()
    {
        return ScholarshipRequirementItem::where('requirement_id', $this->id)->where('type', 'gwa')->exists();
    }
}
