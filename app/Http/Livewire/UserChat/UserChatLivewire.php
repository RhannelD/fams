<?php

namespace App\Http\Livewire\UserChat;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\UserChat;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class UserChatLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    
    public $rid;
    public $chat;

    public $chat_count = 8;

    protected $listeners = [
        'refresh' => '$refresh',
        'set_receiver' => 'set_receiver'
    ];

    protected $rules = [
        'chat' => 'required|min:1|max:999',
    ];

    protected $messages = [
        'chat.required' => '',
        'chat.min' => '',
        'chat.max' => 'The message must not be greater than 999 characters.',
    ];

    protected $queryString = [
        'rid' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        if ( Auth::guest() ) abort(403);
    }

    public function hydrate()
    {
        if ( Auth::guest() ) 
            return redirect()->route('user.chat', ['rid' => $this->rid]);
    }

    public function dehydrate()
    {
        $this->emitTo('user-chat.user-chat-list-livewire', 'refresh');
    }

    public function set_receiver($rid)
    {
        $this->rid = $rid;
        $this->page = 1;
        $this->reset('chat_count');
    }

    public function render()
    {
        $this->set_seen();

        return view('livewire.pages.user-chat.user-chat-livewire', [
                'chat_user' => $this->get_chat_user(),
                'messages'  => $this->get_messages(),
                'messages_count' => $this->get_messages_count(),
                'search_users' => $this->get_search(),
            ])
            ->extends('livewire.main.chat-livewire');
    }

    protected function set_seen()
    {
        UserChat::where('sender_id', $this->rid)
            ->where('receiver_id', Auth::id())
            ->whereNull('seen')
            ->update(['seen' => Carbon::now()]);
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
            ->take($this->chat_count)
            ->get()
            ->reverse()
            ->values();
    }

    protected function get_messages_count()
    {
        if ( empty($this->rid) ) 
            return 0;
        $rid = $this->rid;
        return UserChat::where(function ($query) use ($rid) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $rid);
            })
            ->orWhere(function ($query) use ($rid) {
            $query->where('sender_id', $rid)
                ->where('receiver_id', Auth::id());  
            })
            ->count();
    }

    protected function get_search()
    {
        if ( isset($this->rid) || empty($this->search) ) 
            return [];

        return User::where('id', '!=', Auth::id())
            ->whereNameOrEmail($this->search)
            ->orderBy('firstname')
            ->orderBy('lastname')
            ->paginate(15);
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        $this->page = 1;
    }

    public function load_more()
    {
        $this->chat_count += 8;
    }

    public function send()
    {
        $this->validate();
        
        if ( Auth::guest() || !User::where('id', $this->rid)->exists() ) 
            return;

        $message = UserChat::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $this->rid,
                'chat' => $this->chat,
                'seen' => null,
            ]);

        if ( $message->wasRecentlyCreated ) {
            $this->chat = '';
            $this->chat_count ++;
        }
    }
}
