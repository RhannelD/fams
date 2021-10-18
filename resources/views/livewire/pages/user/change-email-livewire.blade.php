<div>
    <div wire:ignore.self class="modal fade" id="update-email-modal" tabindex="-1" aria-labelledby="update-email-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="update-email-modal-label">
                        <strong>Update Email</strong>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if (!$verified)
                        <h4>Verify Password</h4>
                        <div>
                            <div class="form-group">
                                <label for="verify_password">Enter Password</label>
                                <input wire:model.lazy='password' type="password" class="form-control" id="verify_password">
                                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @elseif ($verified && !$valid_email )
                        <h4>Update Email</h4>
                        <div>
                            <div class="form-group">
                                <label for="email_address">Email address</label>
                                <input wire:model.lazy='email' type="email" class="form-control" id="email_address">
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @else
                        <h4>Verify Email</h4>
                        <div>
                        </div>
                        <div class="form-row">
                            <div class="col-auto">
                                <div class="form-group">
                                    <label for="email_address">Verification Code</label>
                                    <input wire:model.lazy='code' type="text" class="form-control" id="code">
                                    @error('code') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-secondary"
                            wire:click='send_code'
                            >
                            <i id="send_code_load" class="fas fa-spinner fa-spin"
                                wire:loading
                                wire:target='send_code'
                                >
                            </i>
                            Re-send Code
                        </button>
                        @if (session()->has('message-success'))
                           <div class="alert alert-success my-1">
                                {{ session('message-success') }}
                            </div>
                        @endif
                        @if (session()->has('message-error'))
                           <div class="alert alert-danger my-1">
                                {{ session('message-error') }}
                            </div>
                        @endif
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    @if (!$verified)
                        <button type="button" class="btn btn-success"
                            wire:click='verify'
                            >
                            <i id="verify_password_load" class="fas fa-spinner fa-spin"
                                wire:loading
                                wire:target='verify'
                                >
                            </i>
                            Verify
                        </button>
                    @elseif ($verified && !$valid_email )
                        <button type="button" class="btn btn-success"
                            wire:click='save'
                            >
                            <i id="update_email_load" class="fas fa-spinner fa-spin"
                                wire:loading
                                wire:target='save'
                                >
                            </i>
                            Save
                        </button>
                    @else
                        <button type="button" class="btn btn-success"
                            wire:click='verify_email'
                            >
                            <i id="verify_code_load" class="fas fa-spinner fa-spin"
                                wire:loading
                                wire:target='verify_email'
                                >
                            </i>
                            Verify
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
