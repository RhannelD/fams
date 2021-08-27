<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipOfficerInvite extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'scholarship_id',
        'email',
        'token',
    ];

    
    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'scholarship_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    
    public function scopeWhereToken($query, $token)
    {
        return $query->where('token', $token);
    }


}
