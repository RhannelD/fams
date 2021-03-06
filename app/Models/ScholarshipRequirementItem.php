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

    public function response_files()
    {
        return $this->hasMany(ScholarResponseFile::class, 'item_id', 'id');
    }

    public function response_answer()
    {
        return $this->hasMany(ScholarResponseAnswer::class, 'item_id', 'id');
    }

    public function response_units()
    {
        return $this->hasMany(ScholarResponseUnit::class, 'item_id', 'id');
    }

    public function response_gwas()
    {
        return $this->hasMany(ScholarResponseGwa::class, 'item_id', 'id');
    }

    public function requirement()
    {
        return $this->belongsTo(ScholarshipRequirement::class, 'requirement_id', 'id');
    }

    public function is_for_analytics()
    {
        return in_array($this->type, ['gwa', 'units']);
    }
}
