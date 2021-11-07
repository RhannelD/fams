<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarResponseGwa extends Model
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
        'gwa',
    ];

    public function response()
    {
        return $this->belongsTo(ScholarResponse::class, 'response_id', 'id');
    }
}
