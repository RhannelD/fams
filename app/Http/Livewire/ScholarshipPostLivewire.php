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

    public function mount($scholarship_id)
    {
        if ($this->verifyUser()) return;

        $this->scholarship_id = $scholarship_id;

        $this->post = new ScholarshipPost;

        $this->requirements = ScholarshipRequirement::where('scholarship_requirements.scholarship_id', $this->scholarship_id)
            ->whereNotIn('scholarship_requirements.id', $this->added_requirements)
            ->orderBy('scholarship_requirements.id', 'desc')
            ->get();
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
            foreach ($this->added_requirements as $requirement_id) {
                $requirement_list = new ScholarshipPostLinkRequirement;
                $requirement_list->post_id = $this->post->id;
                $requirement_list->requirement_id = $requirement_id;
                $requirement_list->save();
            }

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
