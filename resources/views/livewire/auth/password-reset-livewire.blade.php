<div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-info">
        <a class="navbar-brand text-light" href="{{ route('index') }}">
            <img src="{{ asset('img/scholarship-icon.png') }}" width="30" height="30" alt="" style="margin-top: -5px;">
            <Strong>
                {{ config('app.name') }}
            </Strong>
        </a>
    </nav>

    <div class="container">
        @if ( $password_reset )
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <hr>
                    <h3>
                        <strong>
                            Password Reset
                        </strong>
                    </h3>
                    <p>
                        Please enter your new password for 
                        <strong>
                            {{ $email }}
                        </strong>
                    </p>
                    <form wire:submit.prevent="submit">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="password-new">New Password</label>
                                <input wire:model.lazy='new_password' type="password" class="form-control" id="password-new">
                                @error('new_password') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password-confirm">Confirm Password</label>
                                <input wire:model.lazy='confirm_password' type="password" class="form-control" id="password-confirm">
                                @error('confirm_password') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <hr>
                    <div class="alert alert-danger">
                        This password reset link might be expired or incomplete.
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
