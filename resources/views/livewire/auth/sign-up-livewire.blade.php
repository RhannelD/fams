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
                        @include('livewire.form.user-form-livewire')
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
                            <button wire:click='next' class="btn btn-success">
                                Next
                            </button>
                        </div>
                    </div>
                    <div wire:ignore.self class="tab-pane pt-3 fade" id="verify" role="tabpanel" aria-labelledby="verify-tab">
                        @if (session()->has('message-success'))
                            <div class="alert alert-success">
                                {{ session('message-success') }}
                                {{ $verification_code }}
                            </div>
                        @endif
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
                                <button wire:click='save' class="btn btn-success mb-2">Submit</button>
                            </div>
                            <div class="col-auto">
                                <button wire:click='resend_code' class="btn btn-primary mb-2">Resend Code</button>
                            </div>
                        </div>
                        @error('code') <span class="text-danger">{{ $message }}</span> @enderror

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
