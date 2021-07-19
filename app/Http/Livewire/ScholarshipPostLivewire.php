<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipPost;
use App\Models\ScholarshipPostLinkRequirement;
use App\Models\ScholarshipRequirement;

class ScholarshipPostLivewire extends Component
{
    public $scholarship_id;
    public $post;
    public $post_id;

    public $show_requirement = false;
    public $requirements;
    public $added_requirements = [];


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

    public function mount($scholarship_id, $post_id = null)
    {
        if ($this->verifyUser()) return;

        $this->scholarship_id = $scholarship_id;

        $this->requirements = ScholarshipRequirement::where('scholarship_requirements.scholarship_id', $this->scholarship_id)
        ->whereNotIn('scholarship_requirements.id', $this->added_requirements)
        ->orderBy('scholarship_requirements.id', 'desc')
        ->get();

        $this->post = new ScholarshipPost;
        if ( isset($post_id) ) {
            $this->post_id = $post_id;
            $this->post = ScholarshipPost::find($post_id);

            $links = ScholarshipPostLinkRequirement::where('post_id', $post_id)
                ->get();

            foreach ($links as $link) {
                array_push($this->added_requirements, $link->requirement_id);
            }
        }
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
        
        if ( !isset($this->post_id) ) {
            $this->post->scholarship_id = $this->scholarship_id;
            $this->post->user_id = Auth::id();
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

            if ( isset($this->post_id) ) {
                $this->post = ScholarshipPost::find($this->post_id);
            } else {
                $this->post = new ScholarshipPost;
            }

            $this->emitUp('post_updated');
        }
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
