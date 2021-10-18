<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScholarResponse extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'requirement_id',
        'submit_at',
        'approval',
    ];

    
    public function cant_be_edit()
    {
        return isset($this->approval);
    }

    public function comments()
    {
        return $this->hasMany(ScholarResponseComment::class, 'response_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function requirement()
    {
        return $this->belongsTo(ScholarshipRequirement::class, 'requirement_id', 'id');
    }


    public function submmited_on_time()
    {
        $requirement = ScholarshipRequirement::find($this->requirement_id);
        return $requirement->end_at > $this->submit_at;
    }

    public function is_submitted()
    {
        return isset($this->submit_at);
    }

    public function is_late_to_submit()
    {
        $requirement = ScholarshipRequirement::find($this->requirement_id);
        if ( isset($requirement->enable) ) 
            return !$requirement->enable;

        return Carbon::parse($requirement->end_at)->format('Y-m-d h:i:s') < Carbon::now()->format('Y-m-d h:i:s');
    }
}
