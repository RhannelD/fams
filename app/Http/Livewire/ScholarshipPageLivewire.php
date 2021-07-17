<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipPost;

class ScholarshipPageLivewire extends Component
{

    public $scholarship_id;
    public $post_count = 10;
    
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
        $posts = ScholarshipPost::where('scholarship_id', $this->scholarship_id)
            ->orderBy('id', 'desc')
            ->take($this->post_count)
            ->get();

        $count = ScholarshipPost::where('scholarship_id', $this->scholarship_id)->count();

        $show_more = ($count > count($posts));

        return view('livewire.pages.scholarship-page-livewire.scholarship-page-livewire', [
            'posts' => $posts,
            'show_more' => $show_more
        ]);
    }

    public function load_more()
    {
        $this->post_count += 10;
    }
}
