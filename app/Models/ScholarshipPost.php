<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipPost extends Model
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
        'title',
        'post',
        'promote',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'scholarship_id', 'id');
    }

    public function requirement_links()
    {
        return $this->hasMany(ScholarshipPostLinkRequirement::class, 'post_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(ScholarshipPostComment::class, 'post_id', 'id');
    }

    
    public function scopeWherePromote($query)
    {
        return $query->where('promote', true);
    }
}
