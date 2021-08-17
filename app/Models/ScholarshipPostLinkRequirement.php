<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipPostLinkRequirement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id',
        'requirement_id',
    ];
    
    public function requirement()
    {
        return $this->belongsTo(ScholarshipRequirement::class, 'requirement_id', 'id');
    }
}
