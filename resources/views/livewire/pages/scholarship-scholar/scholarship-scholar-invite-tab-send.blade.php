<div class="form-row">
    @if ( count($categories)>1 )
        <div class="col-md-8">
            <div class="form-group">
                <label for="name_email">Enter name or email</label>
                <input wire:model.debounce.1000ms="name_email" type="text" class="form-control" placeholder="Enter name or email" id="name_email">
            </div>
        </div>
        <div class="col-md-4"> 
            <div class="form-group">
                <label for="category">Category</label>
                <select  wire:model="category_id" class="form-control" id="category">
                    @forelse ($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->category }}
                        </option>
                    @empty
                        <option>None</option>
                    @endforelse
                </select>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="acad_year">Academic Year</label>
            <select wire:model="acad_year" id="acad_year" class="form-control">
                @for ($year = $max_acad_year; $year>2016; $year--)
                    <option value="{{ $year }}">
                        {{ $year }}-{{ $year+1 }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="acad_semester">Semester</label>
            <select wire:model="acad_sem" id="acad_semester" class="form-control">
                <option value="1">First Semester</option>
                <option value="2">Second Semester</option>
            </select>
        </div>
    @else
        <div class="form-group col-md-6">
            <label for="name_email">Enter name or email</label>
            <input wire:model.debounce.1000ms="name_email" type="text" class="form-control" placeholder="Enter name or email" id="name_email">
        </div>
        <div class="form-group col-md-3 col-6">
            <label for="acad_year">Academic Year</label>
            <select wire:model="acad_year" id="acad_year" class="form-control">
                @for ($year = $max_acad_year; $year>2016; $year--)
                    <option value="{{ $year }}">
                        {{ $year }}-{{ $year+1 }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="form-group col-md-3 col-6">
            <label for="acad_semester">Semester</label>
            <select wire:model="acad_sem" id="acad_semester" class="form-control">
                <option value="1">First Semester</option>
                <option value="2">Second Semester</option>
            </select>
        </div>
    @endif
</div>
@if (session()->has('message-success'))
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif
@if (session()->has('message-error'))
    <div class="alert alert-danger mt-0">
        {{ session('message-error') }}
    </div>
@endif
@empty($name_email)
    <div class="alert alert-info">
        Please enter the scholar's name or email.
    </div>
    <div class="alert alert-info">
        Inviting a non-registered email will be requested for signing-up before accepting.
    </div>
@endempty
@if(!$errors->has('name_email') && !empty($name_email) )
    <h6 class="mb-1">Invite via email</h6>
    <hr class="my-1">
    <div class="input-group mb-3">
        <input type="text" class="form-control bg-white border-success" value="{{ $name_email }}" readonly>
        <div class="input-group-append">
            <button class="btn btn-success" type="button"
                wire:click="invite_email('{{ $name_email }}')" 
                wire:loading.attr='disabled'
                >
                <i class="fas fa-envelope" id="invite_email_{{ $name_email }}"
                    wire:loading.remove 
                    wire:target="invite_email('{{ $name_email }}')"
                    >
                </i>
                <i class="fas fa-spinner fa-spin" id="invite_email_load_{{ $name_email }}"
                    wire:loading 
                    wire:target="invite_email('{{ $name_email }}')">
                </i>
                Invite
            </button>
        </div>
    </div>
@endif

@isset($search_officers) 
    <h6 class="mb-1">Scholar results</h6>
    <hr class="my-1">
    @forelse ($search_officers as $officer)
        <div class="input-group mb-1">
            <input type="text" class="form-control bg-white border-success" value="{{ $officer->flname() }} / {{ $officer->email }}" readonly>
            <div class="input-group-append">
                <button class="btn btn-success" type="button"
                    wire:click="invite_email('{{ $officer->email }}')" 
                    wire:loading.attr='disabled'
                    >
                    <i class="fas fa-envelope" id="invite_email_{{ $officer->email }}"
                        wire:loading.remove 
                        wire:target="invite_email('{{ $officer->email }}')">
                    </i>
                    <i class="fas fa-spinner fa-spin" id="invite_email_load_{{ $officer->email }}"
                        wire:loading 
                        wire:target="invite_email('{{ $officer->email }}')">
                    </i>
                    Invite
                </button>
            </div>
        </div>
    @empty
        <input type="text" class="form-control bg-white" value="No results..." readonly>
    @endforelse
@endisset

<div class="alert alert-success my-3">
    You may also 
    <a href="{{ route('scholarship.scholar.invite', [$scholarship_id]) }}">
        import excel file
    </a>.
</div>
