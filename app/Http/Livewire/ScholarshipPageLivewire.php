<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipPost;
use App\Models\ScholarshipPostComment;
use App\Models\ScholarshipPostLinkRequirement;
use Illuminate\Support\Facades\DB;

class ScholarshipPageLivewire extends Component
{

    public $scholarship_id;
    public $post_count = 10;
    
    protected $listeners = ['post_updated' => '$refresh'];

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
    }
    
    
    public function render()
    {
        $count = ScholarshipPost::where('scholarship_id', $this->scholarship_id)->count();
        $show_more = ($count > $this->post_count);

        $this->dispatchBrowserEvent('remove:modal-backdrop');

        return view('livewire.pages.scholarship-page-livewire.scholarship-page-livewire', [
                'posts' => $this->get_posts(),
                'show_more' => $show_more
            ])
            ->extends('livewire.main.main-livewire');
    }

    protected function get_posts()
    {
        return ScholarshipPost::where('scholarship_id', $this->scholarship_id)
            ->orderBy('id', 'desc')
            ->take($this->post_count)
            ->get();
    }

    public function load_more()
    {
        $this->post_count += 10;
    }
}
