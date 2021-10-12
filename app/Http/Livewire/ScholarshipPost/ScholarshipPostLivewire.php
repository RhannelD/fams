<?php

namespace App\Http\Livewire\ScholarshipPost;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ScholarshipPost;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipPostLinkRequirement;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ScholarshipPostLivewire extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $invalid_session = false;
    public $search;
    
    public $scholarship_id;
    public $post_id;
    public $post;

    public $show_requirement = false;
    public $added_requirements = [];


    protected $rules = [
        'post.title' => 'max:200',
        'post.post' => 'required|string|min:1|max:16000000',
        'post.promote' => 'boolean',
    ];
    
    public function getQueryString()
    {
        return [];
    }

    public function hydrate()
    {
        if ( Auth::guest() 
            || (isset($this->post_id) && Auth::user()->cannot('update', $this->get_post())) 
            || (is_null($this->post_id) && Auth::user()->cannot('create', [ScholarshipPost::class, $this->scholarship_id]))
            ) {
            $this->invalid_session = true;
            $this->emitUp('post_updated');
        } else {
            $this->invalid_session = false;
        }
    }

    public function mount($scholarship_id, $post_id = null)
    {
        $this->scholarship_id = $scholarship_id;
        $this->post_id = $post_id;
        $this->set_post();
    }

    protected function set_post()
    {
        $this->post = new ScholarshipPost;
        $this->post->promote = false;
        if ( isset($this->post_id) ) {
            $post = $this->get_post();
            if ( is_null($post) ) {
                $this->post_id = null;
                return;
            }

            $this->post->title   = $post->title;
            $this->post->post    = $post->post;
            $this->post->promote = $post->promote;
            
            foreach ($post->requirement_links as $link) {
                array_push($this->added_requirements, $link->requirement_id);
            }
        }
    }

    public function render()
    {
        return view('livewire.pages.scholarship-post.scholarship-post-livewire', [
            'requirements' => $this->get_requirements(),
            'display_requirements' => $this->get_requirements_added(),
        ]);
    }
    
    protected function get_post()
    {
        return ScholarshipPost::find($this->post_id);
    }

    protected function get_requirements()
    {
        $search = $this->search;
        return ScholarshipRequirement::where('scholarship_id', $this->scholarship_id)
            ->whereNotIn('id', $this->added_requirements)
            ->when(isset($search), function ($query) use ($search) {
                $query->where('requirement', 'like', "%$search%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
    }

    protected function get_requirements_added()
    {
        return ScholarshipRequirement::where('scholarship_id', $this->scholarship_id)
            ->whereIn('id', $this->added_requirements)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        if ($this->invalid_session) return;

        $this->validate();

        if ( is_null($this->post_id) ) {
            $this->post->scholarship_id = $this->scholarship_id;
            $this->post->user_id = Auth::id();
        } else {
            $post = $this->get_post();
            if ( is_null($post) ) {
                $this->dispatchBrowserEvent('close_post_modal');
                $this->emitUp('post_updated');
                return;
            }
            $post->title   = $this->post->title;
            $post->post    = $this->post->post;
            $post->promote = $this->post->promote;
            $this->post    = $post;
        }

        if ($this->post->save()) {
            ScholarshipPostLinkRequirement::where('post_id', $this->post->id)
                ->whereNotIn('requirement_id', $this->added_requirements)
                ->delete();

            foreach ($this->added_requirements as $requirement_id) {
                $requirement_list = ScholarshipPostLinkRequirement::firstOrCreate([
                        'post_id' => $this->post->id,
                        'requirement_id' => $requirement_id
                    ]);
            }

            $this->dispatchBrowserEvent('close_post_modal');

            if ( !isset($this->post_id) ) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',  
                    'message' => 'Post Successfully', 
                ]);
            }

            $this->dispatchBrowserEvent('remove:modal-backdrop');
            $this->emitUp('post_updated');
        }

        $this->set_post();
    }

    public function show_requirements()
    {
        $this->show_requirement = !$this->show_requirement;
    }

    public function add_requirement($requirement_id)
    {
        $this->added_requirements[] = $requirement_id;

        $this->show_requirement = false;
    }

    public function remove_requirement($requirement_id)
    {
        $this->added_requirements = array_diff($this->added_requirements, array($requirement_id));
    }
}
