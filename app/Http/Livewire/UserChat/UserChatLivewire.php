<?php

namespace App\Http\Livewire\UserChat;

use App\Models\User;
use Livewire\Component;
use App\Models\UserChat;
use Illuminate\Support\Facades\Auth;

class UserChatLivewire extends Component
{
    public $rid = 44;

    protected $queryString = [
        'rid' => ['except' => ''],
    ];

    public function render()
    {
        return view('livewire.pages.user-chat.user-chat-livewire', [
                'chat_user' => $this->get_chat_user(),
                'messages'  => $this->get_messages(),
            ])
            ->extends('livewire.main.chat-livewire');
    }

    protected function get_chat_user()
    {
        return User::find($this->rid);
    }

    protected function get_messages()
    {
        if ( empty($this->rid) ) 
            return [];

        $rid = $this->rid;
        return UserChat::where(function ($query) use ($rid) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $rid);
            })
            ->orWhere(function ($query) use ($rid) {
            $query->where('sender_id', $rid)
                ->where('receiver_id', Auth::id());  
            })
            ->orderBy('id', 'desc')
            ->take(10)
            ->get()
            ->reverse()
            ->values();
    }
}
