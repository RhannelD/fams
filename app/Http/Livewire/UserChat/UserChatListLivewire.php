<?php

namespace App\Http\Livewire\UserChat;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserChatListLivewire extends Component
{
    public $rid;

    public $convo_count = 8;

    protected $listeners = [
        'refresh' => '$refresh',
        'set_receiver' => 'set_receiver',
    ];

    public function render()
    {
        return view('livewire.pages.user-chat.user-chat-list-livewire', [
            'convos' => $this->get_convos(),
            'convo_number' => $this->get_convo_number(),
        ]);
    }

    protected function get_convos()
    {
        $user_id = Auth::id();
        return User::where('id', '!=', Auth::id())
            ->where(function ($query){
                $query->whereHas('chat_send', function ($query) {
                    $query->where('receiver_id', Auth::id());
                })
                ->orWhereHas('chat_receive', function ($query) {
                    $query->where('sender_id', Auth::id());
                });
            })
            ->select('users.*')
            ->addSelect(
                DB::raw("(
                    select user_chats.id 
                    from user_chats
                    where (
                        sender_id = {$user_id} 
                        and receiver_id = users.id
                    ) or (
                        receiver_id = {$user_id} 
                        and sender_id = users.id
                    )
                    ORDER BY id DESC LIMIT 1
                ) as last_chat_id")
            )
            ->orderBy('last_chat_id', 'desc')
            ->take($this->convo_count)
            ->get();
    }

    protected function get_convo_number()
    {
        return User::where('id', '!=', Auth::id())
            ->where(function ($query){
                $query->whereHas('chat_send', function ($query) {
                    $query->where('receiver_id', Auth::id());
                })
                ->orWhereHas('chat_receive', function ($query) {
                    $query->where('sender_id', Auth::id());
                });
            })
            ->count();
    }

    public function set_receiver($rid)
    {
        $this->rid = $rid;
        $this->emitTo('user-chat.user-chat-livewire', 'set_receiver', $rid);
    }
    
    public function load_more()
    {
        $this->convo_count += 8;
    }
}
