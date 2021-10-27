<?php

namespace App\Http\Livewire\ScholarshipPage;

use Livewire\Component;
use App\Models\Scholarship;
use App\Models\ScholarshipPost;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipPostComment;
use App\Models\ScholarshipPostLinkRequirement;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ScholarshipPageLivewire extends Component
{
    use AuthorizesRequests;
    
    public $scholarship_id;
    public $post_count = 10;
    
    protected $listeners = ['post_updated' => '$refresh'];

    public function mount($scholarship_id)
    {
        $this->scholarship_id = $scholarship_id;
        abort_if(!Scholarship::find($scholarship_id), '404');
        $this->authorize('viewAny', [ScholarshipPost::class, $scholarship_id]);
    }

    public function hydrate()
    {
        if ( Auth::guest() || !Auth::user()->can('viewAny', [ScholarshipPost::class, $this->scholarship_id]) ) {
            return redirect()->route('scholarship.home', [$this->scholarship_id]);
        }
    }

    public function render()
    {
        $count = ScholarshipPost::where('scholarship_id', $this->scholarship_id)->count();
        $show_more = ($count > $this->post_count);

        $this->dispatchBrowserEvent('remove:modal-backdrop');

        return view('livewire.pages.scholarship-page.scholarship-page-livewire', [
                'posts' => $this->get_posts(),
                'show_more' => $show_more,
                'url' => url()->current(),
            ])
            ->extends('livewire.main.main-livewire');
    }

    protected function get_posts()
    {
        return ScholarshipPost::where('scholarship_id', $this->scholarship_id)
            ->orderBy('updated_at', 'desc')
            ->take($this->post_count)
            ->get();
    }

    public function load_more()
    {
        $this->post_count += 10;
    }
}
