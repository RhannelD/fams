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
                                <label for="password">Enter Password</label>
                                <input wire:model.lazy='password' type="password" class="form-control" id="password">
                                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @else
                        <h4>Update Email</h4>
                        <div>
                            <div class="form-group">
                                <label for="email_address">Email address</label>
                                <input wire:model.lazy='email' type="email" class="form-control" id="email_address" aria-describedby="emailHelp">
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    @if (!$verified)
                        <button type="button" class="btn btn-success"
                            wire:click='verify'
                            >
                            <i id="update_email_load" class="fas fa-spinner fa-spin"
                                wire:loading
                                wire:target='verify'
                                >
                            </i>
                            Verify
                        </button>
                    @else
                        <button type="button" class="btn btn-success"
                            wire:click='save'
                            >
                            Save
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
