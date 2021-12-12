<?php

namespace App\Http\Livewire\ScholarScholarship;

use Livewire\Component;
use App\Traits\YearSemTrait;
use App\Models\ScholarshipPost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ScholarScholarshipFindLivewire extends Component
{
    use YearSemTrait;
    use AuthorizesRequests;
    
    public $post_count = 10;

    public function hydrate()
    {
        if ( $this->is_not_scholar() ) {
            return redirect()->route('scholar.scholarship');
        }
    }

    public function is_not_scholar()
    {
        return Auth::guest() || !Auth::user()->is_scholar();
    }

    public function mount()
    {
        if ( $this->is_not_scholar() ) abort('403', 'THIS ACTION IS UNAUTHORIZED.');
    }

    public function render()
    {
        return view('livewire.pages.scholar-scholarship.scholar-scholarship-find-livewire', [
                'posts' => $this->get_posts(),
                'show_more' => $this->get_show_more(),
            ]);
    }

    protected function get_posts()
    {
        $acad_year = $this->get_acad_year();
        $acad_sem  = $this->get_acad_sem();

        return ScholarshipPost::with('scholarship')
            ->wherePromote()
            ->whereDoesntHave('scholarship', function ($query) use ($acad_year, $acad_sem) {
                $query->whereHas('categories', function ($query) use ($acad_year, $acad_sem) {
                    $query->whereHas('scholars', function ($query) use ($acad_year, $acad_sem) {
                        $query->where('user_id', Auth::id())
                            ->where('acad_year', $acad_year)
                            ->where('acad_sem', $acad_sem);
                    });
                });
            })
            ->orderBy('updated_at', 'desc')
            ->when(isset($this->post_count), function ($query) {
                $query->take($this->post_count);
            })
            ->get();
    }

    protected function get_show_more()
    {
        $count = ScholarshipPost::with('scholarship')
            ->wherePromote()
            ->whereDoesntHave('scholarship', function ($query) {
                $query->whereHas('categories', function ($query) {
                    $query->whereHas('scholars', function ($query) {
                        $query->where('user_id', Auth::id());
                    });
                });
            })
            ->count();

        return $this->post_count < $count;
    }
    
    public function load_more()
    {
        $this->post_count += 10;
    }
}
