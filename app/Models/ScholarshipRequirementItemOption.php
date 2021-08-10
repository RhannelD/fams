<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipRequirementItemOption extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'option',
    ];

    public function responses()
    {
        return $this->hasMany(ScholarResponseOption::class, 'option_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo(ScholarshipRequirementItem::class, 'item_id', 'id');
    }
}
