<?php

namespace App\Http\Livewire\ScholarshipScholar;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Traits\YearSemTrait;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use App\Mail\ScholarInvitationMail;
use App\Models\ScholarshipCategory;
use App\Imports\ScholarInviteImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\ProcessScholarInviteJob;
use App\Models\ScholarshipScholarInvite;
use Illuminate\Support\Facades\Validator;
use App\Rules\NotScholarOfScholarshipRule;
use Symfony\Component\Console\Input\Input;
use App\Rules\EmailNotExistOrScholarEmailRule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ScholarshipScholarInviteImportExcel extends Component
{
    use AuthorizesRequests;
    use YearSemTrait;
    use WithFileUploads;
    
    public $scholarship_id;
    public $acad_year;
    public $acad_sem;
    public $excel;
    public $modified_array;
    
    public $dataset;
    public $dataset_invalid;

    protected $rules = [
        'excel' => 'file|max:6000',
    ];

    public function hydrate()
    {
        if ( $this->is_user_not_allow() ) {
            return redirect()->route('scholarship.scholar.invite', [$this->scholarship_id]);
        }
    }

    protected function is_user_not_allow()
    {
        return Auth::guest() || Auth::user()->cannot('viewAny', [ScholarshipScholarInvite::class, $this->scholarship_id]);
    }    

    public function mount($scholarship_id)
    {
        $this->scholarship_id = $scholarship_id;
        $this->authorize('viewAny',[ScholarshipScholarInvite::class, $scholarship_id]);
        $this->acad_year = $this->get_acad_year();
        $this->acad_sem  = $this->get_acad_sem();
    }

    public function render()
    {
        $this->process_data();
        return view('livewire.pages.scholarship-scholar.scholarship-scholar-invite-import-excel', [
                'category_count' => $this->get_category_count(),
                'max_acad_year' => $this->get_acad_year(),
            ])
            ->extends('livewire.main.scholarship');
    }

    public function get_category_count()
    {
        return ScholarshipCategory::where('scholarship_id', $this->scholarship_id)->count();
    }

    public function updated($propertyName)
    {
        $this->refreshing();
    }

    protected function valid_file_type()
    {
        return in_array($this->get_file_extension(), ['xlsx']);
    }

    protected function get_file_extension()
    {
        return pathinfo($this->excel->getClientOriginalName(), PATHINFO_EXTENSION);
    }

    public function refreshing()
    {
        $this->validate();

        if ( !$this->valid_file_type() ) {
            return $this->addError('excel', 'Invalid file type!');
        }

        $this->load_excel();
    }

    protected function load_excel()
    {
        if ( $this->is_user_not_allow() ) 
            return;

        $import = new ScholarInviteImport;
        Excel::import($import, $this->excel);
        $this->modified_array = $import->getArray();
    }

    protected function get_validated_data($row)
    {
        $scholarship_id =  $this->scholarship_id;
        return Validator::make($row, [
            'email' => [
                    'required',
                    'email',
                    'regex:/^[a-zA-Z0-9._%+-]+\@g.batstate-u.edu.ph$/i',
                    new EmailNotExistOrScholarEmailRule,
                    new NotScholarOfScholarshipRule($this->scholarship_id)
                ],
            'category' => [
                    'required',
                    Rule::exists('scholarship_categories')->where(function ($query) use ($scholarship_id) {
                        return $query->where('scholarship_id', $scholarship_id);
                    }),
                ],
        ]);
    }

    public function process_data()
    {
        $modified_array = $this->modified_array;
        
        if ( !isset($modified_array) || !is_array($modified_array) ) {
            $this->dataset = null;
            $this->dataset_invalid = null;
            return;
        }

        $categories = ScholarshipCategory::where('scholarship_id', $this->scholarship_id)->get();
        if ( count($categories)==1 ) {
            foreach ($modified_array as $key => $row) {
                $modified_array[$key]['category'] = $categories[0]->category;
            }
        }

        $dataset = [];
        $dataset_invalid = [];
        foreach ($modified_array as $row) {
            $validated_data = $this->get_validated_data($row);

            if ( !$validated_data->fails() ) {
                $user = User::where('email', $row['email'])->first();
                if ($user) 
                    $row['account'] = $user->flname();

                $invite = ScholarshipScholarInvite::where('email', $row['email'])
                    ->whereNull('respond')
                    ->whereScholarship($this->scholarship_id)
                    ->first();

                if ( $invite ) {
                    $row['invite'] = true;
                    $row['sent']   = $invite->sent;
                    $row['invite_id']   = $invite->id;
                } else {
                    $row['invite'] = false;
                }
                
                $dataset[] = $row;
            } else {
                $row['error'] = $validated_data->errors()->all();
                $dataset_invalid[] = $row;
            }
        }

        $this->dataset = $dataset;
        $this->dataset_invalid = $dataset_invalid;
    }

    public function confirm_invite_all()
    {
        if ( Auth::check() && Auth::user()->can('create', [ScholarshipScholarInvite::class, $this->scholarship_id]) ) {
            $this->dispatchBrowserEvent('swal:confirm:invite_all', [
                'type' => 'warning',  
                'message' => 'Invite All?', 
                'text' => 'Invite all email on the valid list!',
                'function' => "invite_all"
            ]);
        }
    }

    public function invite_all()
    {
        if ( Auth::guest() || Auth::user()->cannot('create', [ScholarshipScholarInvite::class, $this->scholarship_id]) ) 
            return;

        $categories = $this->get_categories();
        foreach ($this->dataset as $key => $data) {
            if ( isset($data['invite']) && $data['invite'] ) 
                continue;

            $validated_data = $this->get_validated_data($data);

            if ( $validated_data->fails() ) {
                $this->dataset[$key]['invite'] = false;
                continue;
            }

            $invite = ScholarshipScholarInvite::updateOrCreate([
                    'email' => $data['email'],
                    'category_id' => $categories[$data['category']],
                ], [
                    'acad_year'  => $this->acad_year,
                    'acad_sem'   => $this->acad_sem,
                ]);
            
            if ( $invite ) {
                $this->send_mail($invite);
            }
        }
    }

    protected function get_categories()
    {
        $get_categories = ScholarshipCategory::where('scholarship_id', $this->scholarship_id)->get();

        $categories = [];
        foreach ($get_categories as $category) {
            $categories[trim($category->category)] = $category->id;
        }
        return $categories;
    }

    public function resend_invite($invite_id)
    {
        $invite = ScholarshipScholarInvite::where('id', $invite_id)
            ->whereNull('respond')
            ->whereScholarship($this->scholarship_id)
            ->first();

        if ( $invite ) {
            $invite->sent = null;
            $invite->save();
            $this->send_mail($invite);
        }
    }

    protected function send_mail($invitation)
    {
        if ( is_null($invitation) || isset($invitation->respond) )
            return;
        
        ProcessScholarInviteJob::dispatch($invitation);
    }

    public function confirm_cancel_all()
    {
        if ( $this->if_can_delete_many_invite() ) {
            $this->dispatchBrowserEvent('swal:confirm:cancel_all', [
                'type' => 'warning',  
                'message' => 'Are you sure?', 
                'text' => 'Cancel all invited on the list!',
                'function' => "cancel_all"
            ]);
        }
    }

    protected function if_can_delete_many_invite()
    {
        return Auth::check() && Auth::user()->can('deleteMany', [ScholarshipScholarInvite::class, $this->scholarship_id]);
    }

    public function cancel_all()
    {
        if ( !$this->if_can_delete_many_invite() ) 
            return;

        $scholarship_id = $this->scholarship_id;
        foreach ($this->dataset as $key => $data) {
            $invites = ScholarshipScholarInvite::where('email', $data['email'])
                ->whereHas('category', function ($query) use ($scholarship_id) {
                        $query->where('scholarship_id', $scholarship_id);
                    })
                ->delete();
        }
    }
}
