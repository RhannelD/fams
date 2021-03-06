<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipScholarInvite extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'email',
        'sent',
        'respond',
        'acad_year',
        'acad_sem',
    ];
    
    public function category()
    {
        return $this->belongsTo(ScholarshipCategory::class, 'category_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function scopeWhereScholarship($query, $scholarship_id)
    {
        return $query->whereHas('category', function ($query) use ($scholarship_id) {
                $query->where('scholarship_id', $scholarship_id);
            });
    }
}
