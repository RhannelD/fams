<div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-info">
        <a class="navbar-brand text-light">
            <img src="{{ asset('img/scholarship-icon.png') }}" width="30" height="30" alt="" style="margin-top: -5px;">
            <Strong>
                FAMS
            </Strong>
        </a>
        <button class="navbar-toggler border-white" type="button" data-toggle="collapse" data-target="#demo-navbar" aria-controls="demo-navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="demo-navbar">
            <ul class="navbar-nav mr-auto"></ul>
            <div class="form-inline my-2 my-lg-0">
                <a href="{{ route('login.index') }}" class="btn btn-primary my-2 my-sm-0">
                    Sign-in
                </a>
            </div>
        </div>  
    </nav>

    <div class="container">
        <div class="card border-primary mt-3">
            <div class="card-header bg-primary border-primary text-white">
                <h4 class="my-0">
                    <strong>
                        Student Sign-up
                    </strong>
                </h4>
            </div>
            <div class="card-body">
                <ul wire:ignore class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="form-tab" data-toggle="tab" href="#form" role="tab" aria-controls="form" aria-selected="true">
                            Sign-Up Form
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" id="verify-tab" data-toggle="tab" href="#verify" role="tab" aria-controls="verify" aria-selected="false">
                            Email Verification
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div wire:ignore.self class="tab-pane fade show active pt-3" id="form" role="tabpanel" aria-labelledby="form-tab">
                        <div class="alert alert-info">
                            Please fill-up the form carefully and truthfully. 
                            This will be reflected on scholarship personnel's evaluation.
                        </div>

                        <h4>
                            Scholar's Information
                        </h4>
                        @include('livewire.form.user-form-livewire')

                        <hr class="my-2">
                        <h4>
                            Educational Information
                        </h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="c_school">School</label>
                                    <select wire:model="user_info.school_id" class="form-control" id="c_school">
                                        <option>Select School</option>
                                        @foreach ($schools as $school)
                                            <option value="{{ $school->id }}">{{ $school->school }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_info.school_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="c_course">Course</label>
                                    <select wire:model="user_info.course_id" class="form-control" id="c_course">
                                        <option>Select Course</option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}">{{ $course->course }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_info.course_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="c_year">Year Level</label>
                                    <select wire:model="user_info.year" class="form-control" id="c_year">
                                        <option>Select Year</option>
                                        <option value="1">1st Year</option>
                                        <option value="2">2nd Year</option>
                                        <option value="3">3rd Year</option>
                                        <option value="4">4th Year</option>
                                        <option value="5">5th Year</option>
                                        <option value="6">6th Year</option>
                                        <option value="7">7th Year</option>
                                        <option value="8">8th Year</option>
                                        <option value="9">9th Year</option>
                                        <option value="10">10th Year</option>
                                    </select>
                                    @error('user_info.year') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="c_semester">Semester</label>
                                    <select wire:model="user_info.semester" class="form-control" id="c_semester">
                                        <option>Select Semester</option>
                                        <option value="1">1st Semester</option>
                                        <option value="2">2nd Semester</option>
                                        <option value="3">Summer</option>
                                    </select>
                                    @error('user_info.semester') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-2">
                        <h4>
                            Family Information
                        </h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="c_mother_name">Mother's Name</label>
                                    <input wire:model.lazy="user_info.mother_name" type="text" class="form-control" id="c_mother_name" placeholder="Mother's Name">
                                    @error('user_info.mother_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group mt-3">
                                    <label for="c_mother_birthday">Mother's Birth Date</label>
                                    <input wire:model.lazy="user_info.mother_birthday" type="date" class="date form-control" id="c_mother_birthday">
                                    @error('user_info.mother_birthday') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="c_mother_occupation">Mother's Occupation</label>
                                    <input wire:model.lazy="user_info.mother_occupation" type="text" class="form-control" id="c_mother_occupation" placeholder="Mother's Occupation">
                                    @error('user_info.mother_occupation') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="c_mother_educational_attainment">Mother's Educational Attainment</label>
                                    <input wire:model.lazy="user_info.mother_educational_attainment" type="text" class="form-control" id="c_mother_educational_attainment" placeholder="Mother's Educational Attainment">
                                    @error('user_info.mother_educational_attainment') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <div class="form-check form-check-inline">
                                        <input wire:model.lazy="user_info.mother_living" class="form-check-input" type="radio" name="c_mother_living" id="c_mother_living_1" value="1">
                                        <label class="form-check-label" for="c_mother_living_1">Living</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input wire:model.lazy="user_info.mother_living" class="form-check-input" type="radio" name="c_mother_living" id="c_mother_living_2" value="0">
                                        <label class="form-check-label" for="c_mother_living_2">Deceased</label>
                                    </div>
                                    @error('user_info.mother_living') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="c_father_name">Father's Name</label>
                                    <input wire:model.lazy="user_info.father_name" type="text" class="form-control" id="c_father_name" placeholder="Father's Name">
                                    @error('user_info.father_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group mt-3">
                                    <label for="c_father_birthday">Father's Birth Date</label>
                                    <input wire:model.lazy="user_info.father_birthday" type="date" class="date form-control" id="c_father_birthday">
                                    @error('user_info.father_birthday') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="c_father_occupation">Father's Occupation</label>
                                    <input wire:model.lazy="user_info.father_occupation" type="text" class="form-control" id="c_father_occupation" placeholder="Father's Occupation">
                                    @error('user_info.father_occupation') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="c_father_educational_attainment">Father's Educational Attainment</label>
                                    <input wire:model.lazy="user_info.father_educational_attainment" type="text" class="form-control" id="c_father_educational_attainment" placeholder="Father's Educational Attainment">
                                    @error('user_info.father_educational_attainment') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <div class="form-check form-check-inline">
                                        <input wire:model="user_info.father_living" class="form-check-input" type="radio" name="c_father_living" id="c_father_living_1" value="1">
                                        <label class="form-check-label" for="c_father_living_1">Living</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input wire:model="user_info.father_living" class="form-check-input" type="radio" name="c_father_living" id="c_father_living_2" value="0">
                                        <label class="form-check-label" for="c_father_living_2">Deceased</label>
                                    </div>
                                    @error('user_info.father_living') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-2">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="c_password">Password</label>
                                    <input type="password" wire:model.lazy="password" class="form-control" id="c_password" placeholder="Password">
                                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="c_password_confirm">Confirm Password</label>
                                    <input type="password" wire:model.lazy="password_confirm" class="form-control" id="c_password_confirm" placeholder="Confirm Password">
                                    @error('password_confirm') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-success"
                                wire:click='next' 
                                wire:loading.attr='disabled'
                                >
                                <i class="fas fa-spinner fa-spin"
                                    wire:loading
                                    wire:target='next'
                                    >
                                </i>
                                Next
                            </button>
                        </div>
                    </div>
                    <div wire:ignore.self class="tab-pane pt-3 fade" id="verify" role="tabpanel" aria-labelledby="verify-tab">
                        <h5>
                            Email: 
                            <strong>
                                {{ $user->email }}
                            </strong>
                        </h5>

                        <label for="">Enter verification code </label>
                        <div class="form-row align-items-center">
                            <div class="col-auto">
                                <input wire:model.lazy='code' type="text" class="form-control mb-2" id="inlineFormInput" placeholder="Enter verification code">
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-success mb-2"
                                    wire:click='save' 
                                    wire:loading.attr='disabled'
                                    >
                                    <i class="fas fa-spinner fa-spin"
                                        wire:loading
                                        wire:target='save'
                                        >
                                    </i>
                                    Submit
                                </button>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-primary mb-2"
                                    wire:click='resend_code' 
                                    wire:loading.attr='disabled'
                                    >
                                    <i class="fas fa-spinner fa-spin"
                                        wire:loading
                                        wire:target='resend_code'
                                        >
                                    </i>
                                    Resend Code
                                </button>
                            </div>
                        </div>
                        @error('code') <span class="text-danger">{{ $message }}</span> @enderror
                        <div class="d-flex py-0">
                            @if (session()->has('message-success'))
                                <div class="alert alert-success ">
                                    {{ session('message-success') }}
                                </div>
                            @endif
                            @if (session()->has('message-error'))
                                <div class="alert alert-danger">
                                    {{ session('message-error') }}
                                </div>
                            @endif
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-end">
                            <button wire:click='back' class="btn btn-dark mx-1">
                                Back
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('change:tab', event => {
            $('#verify-tab').removeClass('disabled');
            var tab = '#' + event.detail.tab + '-tab';
            $(tab).tab('show');
            $('#verify-tab').addClass('disabled');
        });
    </script>
</div>
