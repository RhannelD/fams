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

    /**
     * Route notifications for the Nexmo channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForNexmo($notification)
    {
        return '63'.substr($this->phone, 1); 
    }

    /**
     * Get the notification routing information for the Bulk SMS driver.
     *
     * @param \Illuminate\Notifications\Notification|null $notification Notification instance.
     *
     * @return  mixed
     */
    public function routeNotificationForBulkSms($notification = null)
    {
        return '+63'.substr($this->phone, 1); 
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
