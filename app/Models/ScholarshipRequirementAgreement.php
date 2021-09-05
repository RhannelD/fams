<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipRequirementAgreement extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'requirement_id',
        'agreement',
    ];

    public function requirement()
    {
        return $this->belongsTo(ScholarshipRequirement::class, 'requirement_id', 'id');
    }
}
