<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipOfficer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'scholarship_id',
        'position_id',
    ];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'scholarship_id', 'id');
    }
    
    public function position()
    {
        return $this->belongsTo(OfficerPosition::class, 'position_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    
    public function scopeWhereAdmin($query)
    {
        return $query->where('position_id', 1);
    }

    
    public function is_admin()
    {
        return ( $this->position->position == 'Admin' );
    }
}
