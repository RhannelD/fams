<?php

namespace App\Http\Livewire\ScholarshipPost;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipPost;
use App\Models\ScholarshipPostLinkRequirement;
use App\Models\ScholarshipRequirement;

class ScholarshipPostLivewire extends Component
{
    public $scholarship_id;
    public $post_id;
    public $post;

    public $show_requirement = false;
    public $requirements;
    public $added_requirements = [];


    protected $rules = [
        'post.title' => 'max:200',
        'post.post' => 'required|string|min:1|max:16000000',
        'post.promote' => 'boolean',
    ];

    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('dashboard');
            return true;
        }
        return false;
    }

    public function mount($scholarship_id, $post_id = null)
    {
        if ($this->verifyUser()) return;

        $this->scholarship_id = $scholarship_id;

        $this->requirements = ScholarshipRequirement::where('scholarship_requirements.scholarship_id', $this->scholarship_id)
            ->whereNotIn('scholarship_requirements.id', $this->added_requirements)
            ->orderBy('scholarship_requirements.id', 'desc')
            ->get();

        $this->post_id = $post_id;
        $this->set_post();
    }

    protected function set_post()
    {
        $this->post = new ScholarshipPost;
        $this->post->promote = false;
        if ( isset($this->post_id) ) {
            $post = ScholarshipPost::find($this->post_id);
            if ( is_null($post) ) {
                $this->post_id = null;
                return;
            }

            $this->post->title = $post->title;
            $this->post->post  = $post->post;
            
            foreach ($post->requirement_links as $link) {
                array_push($this->added_requirements, $link->requirement_id);
            }
        }
    }

    public function render()
    {
        return view('livewire.pages.scholarship-post.scholarship-post-livewire');
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        if ($this->verifyUser()) return;

        $this->validate();

        if ( is_null($this->post_id) ) {
            $this->post->scholarship_id = $this->scholarship_id;
            $this->post->user_id = Auth::id();
        } else {
            $post = ScholarshipPost::find($this->post_id);
            if ( is_null($post) ) {
                $this->dispatchBrowserEvent('close_post_modal');
                $this->emitUp('post_updated');
                return;
            }
            $post->title = $this->post->title;
            $post->post  = $this->post->post;
            $this->post  = $post;
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
        array_push($this->added_requirements, $requirement_id);

        $this->show_requirement = false;
    }

    public function remove_requirement($requirement_id)
    {
        $this->added_requirements = array_diff($this->added_requirements, array($requirement_id));
    }
}
