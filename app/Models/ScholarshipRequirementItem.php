<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipRequirementItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'requirement_id',
        'item',
        'type',
        'note',
        'position',
    ];

    public function options()
    {
        return $this->hasMany(ScholarshipRequirementItemOption::class, 'item_id', 'id');
    }

    public function requirement()
    {
        return $this->belongsTo(ScholarshipRequirement::class, 'requirement_id', 'id');
    }
}
