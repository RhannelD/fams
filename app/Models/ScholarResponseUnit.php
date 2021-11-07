<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarResponseUnit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'response_id',
        'item_id',
        'units',
    ];

    public function response()
    {
        return $this->belongsTo(ScholarResponse::class, 'response_id', 'id');
    }
}
