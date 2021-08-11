<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->hasMany(ScholarshipRequirementItem::class, 'requirement_id', 'id');
    }
}
