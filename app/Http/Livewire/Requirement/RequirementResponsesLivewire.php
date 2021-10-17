<?php

namespace App\Http\Livewire\Requirement;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ScholarResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RequirementResponsesLivewire extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    
    public $search;
    public $show_row = 10;
    public $promote = 'null';

    public function mount()
    {
        $this->authorize('viewUserResponse', [ScholarResponse::class]);
    }

    public function render()
    {
        return view('livewire.pages.requirement.requirement-responses-livewire', [
                'responses' => $this->get_responses()
            ])
            ->extends('livewire.main.main-livewire');
    }

    protected function get_responses()
    {
        $search = trim($this->search);
        return ScholarResponse::where('user_id', Auth::id())
            ->when(!empty($this->search), function ($query) use ($search) {
                $query->whereHas('requirement', function ($query) use ($search) {
                    $query->where('requirement', 'like', "%{$search}%")
                    ->orWhereHas('scholarship', function ($query) use ($search) {
                        $query->where('scholarship', 'like', "%{$search}%");
                    });
                });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate($this->show_row);
    }
    
    public function updated($name)
    {
        $this->page = 1;
    }
}
