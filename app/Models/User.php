<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usertype',
        'firstname',
        'middlename',
        'lastname',
        'gender',
        'phone',
        'email',
        'password',
        'birthday',
        'birthplace',
        'religion',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scholarship_scholars()
    {
        return $this->hasMany(ScholarshipScholar::class, 'user_id', 'id');
    }

    public function flname()
    {
        return $this->firstname .' '. $this->lastname;
    }

    public function is_admin()
    {
        return ( $this->usertype == 'admin' );
    }
    
    public function is_scholar($scholarship_id)
    {
        return ScholarshipScholar::where('user_id', $this->id)
            ->with('category')
            ->whereHas('category', function ($query) use ($scholarship_id) {
                $query->where('scholarship_id', $scholarship_id);
            })
            ->exists();
    }

    public function get_scholarship_scholar($scholarship_id)
    {
        return ScholarshipScholar::where('user_id', $this->id)
            ->whereHas('category', function ($query) use ($scholarship_id) {
                $query->where('scholarship_id', $scholarship_id);
            })
            ->first();
    }
}
