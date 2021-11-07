<?php

namespace App\Http\Livewire\ScholarshipRequirementResponse;

use Livewire\Component;
use App\Models\ScholarResponse;
use App\Models\ScholarResponseGwa;
use App\Models\ScholarshipScholar;
use Illuminate\Support\Facades\DB;
use App\Models\ScholarResponseUnit;
use Illuminate\Support\Facades\Auth;
use Phpml\Classification\KNearestNeighbors;

class ScholarshipRequirementResponseViewLivewire extends Component
{
    public $response_id;

    protected $listeners = [
        'comment_updated' => '$refresh'
    ];

    public function mount($response_id)
    {
        $this->response_id = $response_id;
    }

    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('assess', $this->get_scholar_response()) ) {
            $this->emitUp('refresh');
        }
    }

    public function render()
    {
        return view('livewire.pages.scholarship-requirement-response.scholarship-requirement-response-view-livewire', [
                'scholar_response' => $this->get_scholar_response(),
                'classifier_knn' => $this->get_classifier_knn(),
            ]);
    }

    public function get_classifier_knn()
    {
        $responses = ScholarResponse::selectRaw('scholar_responses.approval, ROUND(scholar_response_gwas.gwa, 2) as gwa, scholar_response_units.units')
            ->join(with(new ScholarResponseGwa)->getTable(), 'scholar_response_gwas.response_id', '=', 'scholar_responses.id')
            ->join(with(new ScholarResponseUnit)->getTable(), 'scholar_response_units.response_id', '=', 'scholar_responses.id')
            ->whereNotNull('approval')
            ->get();

        $samples = [];
        $labels = [];
        foreach ($responses as $response) {
            $samples[] = [$response->gwa, $response->units];
            $labels[] = ($response->approval) == 1? 'approve': 'denied';
        }

        $classifier = new KNearestNeighbors();
        $classifier->train($samples, $labels);
        return $classifier;
    }

    protected function get_scholar_response()
    {
        $response_id = $this->response_id;
        return ScholarResponse::where('id', $response_id)
            ->with([
                'user', 

                'comments' => function ($query) {
                    $query->with('user');
                },

                'requirement' => function ($query) use ($response_id) {
                    $query->with([
                        'agreements' => function ($query) use ($response_id) {
                            $query->with([
                                'response_agreements' => function ($query) use ($response_id) {
                                    $query->where('response_id', $response_id);
                                }
                            ]);
                        },
                        'items' => function ($query) use ($response_id) {
                            $query->with([
                                'options' => function ($query) use ($response_id) {
                                    $query->with([
                                        'responses' => function ($query) use ($response_id) {
                                            $query->where('response_id', $response_id);
                                        }
                                    ]);
                                },

                                'response_answer' => function ($query) use ($response_id) {
                                    $query->where('response_id', $response_id);
                                },
                                'response_files' => function ($query) use ($response_id) {
                                    $query->where('response_id', $response_id);
                                },
                                'response_units' => function ($query) use ($response_id) {
                                    $query->where('response_id', $response_id);
                                },
                                'response_gwas' => function ($query) use ($response_id) {
                                    $query->where('response_id', $response_id);
                                },
                            ]);
                        }
                    ]);
                },
            ])
            ->first();
    }

    protected function is_scholar($scholar_response)
    {
        return $scholar_response->user->is_scholar_of($scholar_response->requirement->scholarship_id);
    }

    public function response_approve()
    {
        $scholar_response = $this->get_scholar_response();
        if ( Auth::guest() || Auth::user()->cannot('assess', $scholar_response) ) 
            return;

        if ( $this->is_scholar($scholar_response) ) {
            $this->response_approval();
            return;
        }

        $this->dispatchBrowserEvent('swal:confirm:approve', [
            'type' => 'info',  
            'message' => 'Add to scholars?', 
            'text' => 'Sender is not yet a scholar here.',
            'function' => "response_approval"
        ]);
    }

    public function response_approval()
    {
        $scholar_response = $this->get_scholar_response();
        if ( Auth::guest() || Auth::user()->cannot('assess', $scholar_response) ) 
            return;

        if ( is_null($scholar_response->approval) ) {
            $scholar_response->approval = true;
            if ( !$scholar_response->save() ) {
                return;
            }

            $this->add_scholar_to_scholarship($scholar_response);

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Successfully Approved!', 
                'text' => 'Scholar\'s response was approved successfully!'
            ]);
        }
    }

    protected function add_scholar_to_scholarship($scholar_response)
    {
        if ( !$this->is_scholar($scholar_response) ) {
            $requirement_category = $scholar_response->requirement->categories->first();
            if ( $requirement_category ) {
                $scholarship_scholar = new ScholarshipScholar;
                $scholarship_scholar->user_id = $scholar_response->user_id;
                $scholarship_scholar->category_id = $requirement_category->category_id;
                $scholarship_scholar->save();
            }
        }
    }

    public function response_deny()
    {
        $scholar_response = $this->get_scholar_response();
        if ( Auth::guest() || Auth::user()->cannot('assess', $scholar_response) ) 
            return;

        if ( !$this->is_scholar($scholar_response) ) {
            $this->response_denial();
            return;
        }

        $confirm = $this->dispatchBrowserEvent('swal:confirm:response_delete', [
            'type' => 'warning',  
            'message' => 'Deny and removed to scholars?', 
            'text' => 'Sender will be remove as scholar upon denying.',
            'function' => "response_denial"
        ]);
    }

    public function response_denial()
    {
        $scholar_response = $this->get_scholar_response();
        if ( Auth::guest() || Auth::user()->cannot('assess', $scholar_response) ) 
            return;

        if ( is_null($scholar_response->approval) ) {
            $scholar_response->approval = false;
            if ( $scholar_response->save() ) {
                $this->remove_scholar_to_scholarship($scholar_response);

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',  
                    'message' => 'Successfully Denied!', 
                    'text' => 'Scholar\'s response was denied successfully!'
                ]);
            }
        }
    }

    protected function remove_scholar_to_scholarship($scholar_response)
    {
        $scholarship_scholar = $scholar_response->user->get_scholarship_scholar($scholar_response->requirement->scholarship_id);
        if ( isset($scholarship_scholar) )
            $scholarship_scholar->delete();  
    }

    public function response_delete_confirm()
    {
        $scholar_response = $this->get_scholar_response();
        if ( Auth::guest() || Auth::user()->cannot('assess', $scholar_response) ) 
            return;

        $confirm = $this->dispatchBrowserEvent('swal:confirm:response_delete', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'Removing response!',
            'function' => "response_delete"
        ]);
    }

    public function response_delete()
    {
        $scholar_response = $this->get_scholar_response();
        if ( Auth::guest() || Auth::user()->cannot('assess', $scholar_response) ) 
            return;

        if ( isset($scholar_response->approval) ) {
            $scholar_response->approval = null;
            if ( $scholar_response->save() ) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',  
                    'message' => 'Approval Removed!', 
                    'text' => 'Response approval was removed successfully!'
                ]);
            }
        }
    }
}
