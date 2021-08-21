<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarResponse extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'requirement_id',
        'submit_at',
        'approval',
    ];

    
    public function cant_be_edit()
    {
        return isset($this->approval);
    }

    public function comments()
    {
        return $this->hasMany(ScholarResponseComment::class, 'response_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
