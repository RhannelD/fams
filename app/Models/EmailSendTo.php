<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSendTo extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email_send_id',
        'email',
        'sent',
    ];
    
    public function email_send()
    {
        return $this->belongsTo(EmailSend::class, 'email_send_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
