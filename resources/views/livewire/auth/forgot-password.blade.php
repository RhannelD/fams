<div>
    <div class="d-flex justify-content-center mt-3">
        <a href="#" type="button" data-toggle="modal" data-target="#forgot-password">
            <h6>
                Forgot password
            </h6>
        </a>
    </div>

    <div wire:ignore.self class="modal fade" id="forgot-password" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="forgot-password-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgot-password-label">Forgot Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @if ( !$sent )
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="c_email">Email Address</label>
                            <input type="email" wire:model.lazy="email" class="form-control" id="c_email" placeholder="juan.delacruz.@g.batstate-u-edu.ph">
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        @if (session()->has('message-success'))
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle"></i>
                                {{ session('message-success') }}
                            </div>
                        @elseif (session()->has('message-error'))
                            <div class="alert alert-danger mb-0">
                                <i class="fas fa-times-circle"></i>
                                {{ session('message-error') }}
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary"
                            wire:click='search'
                            >
                            <i class="fas fa-spinner fa-spin" id="search_load"
                                wire:loading
                                wire:target='search'
                                >
                            </i>
                            Search
                        </button>
                    </div>
                @elseif ( $sent && !$verify_code )
                    <div class="modal-body">
                        <label for="c_email">Email Address</label>
                        <div class="input-group mb-3">
                            <input type="text" wire:model.lazy="email" class="form-control bg-white border-success" aria-describedby="email-resend" disabled>
                            <div class="input-group-append">
                                <button wire:click='search' class="btn btn-success" type="button" id="email-resend">
                                    <i class="fas fa-spinner fa-spin" id="search_load"
                                        wire:loading
                                        wire:target='search'
                                        >
                                    </i>
                                    Resend
                                </button>
                            </div>
                        </div>
                        @if (session()->has('message-success'))
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle"></i>
                                {{ session('message-success') }}
                            </div>
                        @elseif (session()->has('message-error'))
                            <div class="alert alert-danger mb-0">
                                <i class="fas fa-times-circle"></i>
                                {{ session('message-error') }}
                            </div>
                        @endif
                        <hr class="my-2">
                        <div class="form-group">
                            <label for="verify_code">Enter Verification Code</label>
                            <input wire:model.lazy='code' type="text" class="form-control" id="verify_code" placeholder="Code">
                            @error('code') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success"
                            wire:click='verify_code'
                            >
                            <i class="fas fa-spinner fa-spin" id="verify_code_load"
                                wire:loading
                                wire:target='verify_code'
                                >
                            </i>
                            Submit
                        </button>
                    </div>
                @else
                    <div class="modal-body">
                        <label for="c_email">Email Address</label>
                        <div class="input-group mb-3">
                            <input type="text" wire:model.lazy="email" class="form-control bg-white border-success" aria-describedby="email-resend" disabled>
                        </div>
                        <hr class="my-2">
                        <div class="form-group">
                            <label for="password-new">New Password</label>
                            <input wire:model.lazy='new_password' type="password" class="form-control" id="password-new">
                            @error('new_password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="password-confirm">Confirm Password</label>
                            <input wire:model.lazy='confirm_password' type="password" class="form-control" id="password-confirm">
                            @error('confirm_password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success"
                            wire:click='save'
                            >
                            <i class="fas fa-spinner fa-spin" id="save_load"
                                wire:loading
                                wire:target='save'
                                >
                            </i>
                            Save
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
