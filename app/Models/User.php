<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\YearSemTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, YearSemTrait;

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
        'barangay',
        'municipality',
        'province',
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

    /**
     * Route notifications for the Semaphore channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForSemaphore($notification = null)
    {
        return $this->phone;
    }

    public function scholarship_scholars()
    {
        return $this->hasMany(ScholarshipScholar::class, 'user_id', 'id');
    }

    public function scholarship_scholar()
    {
        return $this->hasOne(ScholarshipScholar::class, 'user_id', 'id');
    }

    public function responses()
    {
        return $this->hasMany(ScholarResponse::class, 'user_id', 'id');
    }

    public function response_comments()
    {
        return $this->hasMany(ScholarResponseComment::class, 'user_id', 'id');
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
    
    public function facebook()
    {
        return $this->hasOne(ScholarFacebook::class, 'user_id', 'id');
    }
    
    public function chat_send()
    {
        return $this->hasMany(UserChat::class, 'sender_id', 'id');
    }
    
    public function chat_receive()
    {
        return $this->hasMany(UserChat::class, 'receiver_id', 'id');
    }
    
    public function email_sent_to()
    {
        return $this->hasMany(EmailSendTo::class, 'email', 'email');
    }
    
    public function sms_sent_to()
    {
        return $this->hasMany(SmsSendTo::class, 'user_id', 'id');
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

    public function scopeWhereNotScholarOf($query, $scholarship_id, Carbon $date = null)
    {
        if ( is_null($date) ) {
            $date = Carbon::now();
        }

        return $query->whereDoesntHave('scholarship_scholars', function ($query) use ($scholarship_id, $date) {
                $query->whereYearSem($this->get_acad_year($date), $this->get_acad_sem($date))
                    ->whereHas('category', function ($query) use ($scholarship_id) {
                        $query->where('scholarship_id', '=', $scholarship_id);
                    });
            });
    }

    public function scopeWhereNotPreviousScholarOf($query, $scholarship_id, Carbon $date = null)
    {
        if ( is_null($date) ) {
            $date = Carbon::now();
        }

        $acad_year = $this->get_acad_year($date);
        $acad_sem  = $this->get_acad_sem($date);

        $prev_acad_year = $acad_sem=='1'? $acad_year-1: $acad_year;
        $prev_acad_sem  = $acad_sem=='1'? '2': '1';

        return $query->whereDoesntHave('scholarship_scholars', function ($query) use ($scholarship_id, $prev_acad_year, $prev_acad_sem) {
                $query->whereYearSem($prev_acad_year, $prev_acad_sem)
                    ->whereHas('category', function ($query) use ($scholarship_id) {
                        $query->where('scholarship_id', '=', $scholarship_id);
                    });
            });
    }

    public function scopeWhereScholarOf($query, $scholarship_id, Carbon $date = null)
    {
        if ( is_null($date) ) {
            $date = Carbon::now();
        }

        return $query->whereHas('scholarship_scholars', function ($query) use ($scholarship_id, $date) {
                $query->whereYearSem($this->get_acad_year($date), $this->get_acad_sem($date))
                    ->whereHas('category', function ($query) use ($scholarship_id) {
                        $query->where('scholarship_id', '=', $scholarship_id);
                    });
            });
    }

    public function scopeWherePreviousScholarOf($query, $scholarship_id, Carbon $date = null)
    {
        if ( is_null($date) ) {
            $date = Carbon::now();
        }

        $acad_year = $this->get_acad_year($date);
        $acad_sem  = $this->get_acad_sem($date);

        $prev_acad_year = $acad_sem=='1'? $acad_year-1: $acad_year;
        $prev_acad_sem  = $acad_sem=='1'? '2': '1';

        return $query->whereHas('scholarship_scholars', function ($query) use ($scholarship_id, $prev_acad_year, $prev_acad_sem) {
                $query->whereYearSem($prev_acad_year, $prev_acad_sem)
                    ->whereHas('category', function ($query) use ($scholarship_id) {
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

    public function address()
    {
        return "{$this->barangay}, {$this->municipality}, {$this->province}";
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

    public function is_scholar_of($scholarship_id, Carbon $date = null)
    {
        if ( is_null($date) ) {
            $date = Carbon::now();
        }

        return ScholarshipScholar::where('user_id', $this->id)
            ->whereHas('category', function ($query) use ($scholarship_id) {
                $query->where('scholarship_id', $scholarship_id);
            })
            ->whereYearSem($this->get_acad_year($date), $this->get_acad_sem($date))
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
    
    public function get_scholarship_scholars($acad_year, $acad_sem)
    {
        return ScholarshipScholar::where('user_id', $this->id)
            ->where('acad_year', $acad_year)
            ->where('acad_sem', $acad_sem)
            ->get();
    }

    public function get_invite_count()
    {
        if ( $this->is_scholar() ) {
            return ScholarshipScholarInvite::where('email', $this->email)->whereNull('respond')->count();
        }
        return 0;
    }

    public function get_unseen_chat_count()
    {
        return UserChat::where('receiver_id', $this->id)->whereNull('seen')->groupBy('receiver_id')->count();
    }

    public function get_invite_and_unseen_chat_count()
    {
        return $this->get_invite_count() + $this->get_unseen_chat_count();
    }
}
