<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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

    public function scholarship_officers()
    {
        return $this->hasMany(ScholarshipOfficer::class, 'user_id', 'id');
    }

    public function scholarship_officer()
    {
        return $this->hasOne(ScholarshipOfficer::class, 'user_id', 'id');
    }

    public function responses()
    {
        return $this->hasMany(ScholarResponse::class, 'user_id', 'id');
    }

    public function response_comments()
    {
        return $this->hasMany(ScholarResponseComment::class, 'user_id', 'id');
    }

    public function scholarship_invites()
    {
        return $this->hasMany(ScholarshipOfficerInvite::class, 'email', 'email');
    }
    
    public function scholars_invites()
    {
        return $this->hasMany(ScholarshipScholarInvite::class, 'email', 'email');
    }

    public function email_update()
    {
        return $this->hasOne(EmailUpdate::class, 'user_id', 'id');
    }

    public function scholar_info()
    {
        return $this->hasOne(ScholarInfo::class, 'user_id', 'id');
    }
    

    public function scopeWhereAdmin($query)
    {
        return $query->where('usertype', 'admin');
    }

    public function scopeWhereOfficer($query)
    {
        return $query->where('usertype', 'officer');
    }

    public function scopeWhereScholar($query)
    {
        return $query->where('usertype', 'scholar');
    }

    public function scopeWhereNameOrEmail($query, $search)
    {
        return $query->where(function ($query) use ($search) {
                $query->where('firstname', 'like', "%$search%")
                    ->orWhere('middlename', 'like', "%$search%")
                    ->orWhere('lastname', 'like', "%$search%")
                    ->orWhere(DB::raw('CONCAT(firstname, " ", lastname)'), 'like', "%$search%")
                    ->orWhere(DB::raw('CONCAT(firstname, " ", middlename, " ", lastname)'), 'like', "%$search%")
                    ->orWhere('users.email', 'like', "%$search%");
            });
    }

    public function scopeWhereNotOfficerOf($query, $scholarship_id)
    {
        return $query->whereDoesntHave('scholarship_officers', function ($query) use ($scholarship_id) {
                $query->where('scholarship_id', '=', $scholarship_id);
            });
    }

    public function scopeWhereOfficerOf($query, $scholarship_id)
    {
        return $query->whereHas('scholarship_officers', function ($query) use ($scholarship_id) {
                $query->where('scholarship_id', '=', $scholarship_id);
            });
    }

    public function scopeWhereNotScholarOf($query, $scholarship_id)
    {
        return $query->whereDoesntHave('scholarship_scholars', function ($query) use ($scholarship_id) {
                $query->whereHas('category', function ($query) use ($scholarship_id) {
                        $query->where('scholarship_id', '=', $scholarship_id);
                    });
            });
    }

    public function scopeWhereScholarOf($query, $scholarship_id)
    {
        return $query->whereHas('scholarship_scholars', function ($query) use ($scholarship_id) {
                $query->whereHas('category', function ($query) use ($scholarship_id) {
                        $query->where('scholarship_id', '=', $scholarship_id);
                    });
            });
    }


    public function flname()
    {
        return $this->firstname .' '. $this->lastname;
    }

    public function fmlname()
    {
        return $this->firstname .' '. $this->middlename .' '. $this->lastname;
    }

    public function is_admin()
    {
        return ( $this->usertype == 'admin' );
    }

    public function is_officer()
    {
        return ( $this->usertype == 'officer' );
    }
    
    public function is_scholar()
    {
        return ( $this->usertype == 'scholar' );
    }
    
    public function age()
    {
        return Carbon::parse($this->birthday)->age;
    }

    public function is_scholar_of($scholarship_id)
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
