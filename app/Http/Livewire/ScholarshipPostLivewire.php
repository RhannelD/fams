<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipPost;

class ScholarshipPostLivewire extends Component
{
    public $scholarship_id;
    public $post;

    protected $rules = [
        'post.title' => 'max:200',
        'post.post' => 'required|string|min:1|max:16000000',
    ];

    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('dashboard');
            return true;
        }
        return false;
    }

    public function mount($scholarship_id)
    {
        if ($this->verifyUser()) return;

        $this->scholarship_id = $scholarship_id;

        $this->post = new ScholarshipPost;
    }

    public function render()
    {
        return view('livewire.pages.scholarship-post-livewire.scholarship-post-livewire');
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        if ($this->verifyUser()) return;

        $this->validate();
        
        $this->post->scholarship_id = $this->scholarship_id;
        $this->post->user_id = Auth::id();

        if ($this->post->save()) {
            $this->dispatchBrowserEvent('close_post_modal');

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Post Successfully', 
            ]);

            $this->dispatchBrowserEvent('remove:modal-backdrop');

            $this->post = new ScholarshipPost;

            $this->emitUp('post_updated');
        }
    }
}
