<div>
    <div wire:ignore.self class="modal fade" id="change-password-modal" tabindex="-1" aria-labelledby="change-password-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="change-password-modal-label">
                        <strong>Change Password</strong>
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
                        <h4>Change Password</h4>
                        <div>
                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="new_password">New Password</label>
                                    <input wire:model.lazy='new_password' type="password" class="form-control" id="new_password">
                                    @error('new_password') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col">
                                    <label for="confirm_password">Confirm Password</label>
                                    <input wire:model.lazy='confirm_password' type="password" class="form-control" id="confirm_password">
                                    @error('confirm_password') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    @if (!$verified)
                        <button type="button" class="btn btn-success" id='update_password_button'
                            wire:click='verify'
                            wire:loading.attr='disabled'
                            >
                            <i id="update_password_load" class="fas fa-spinner fa-spin"
                                wire:loading
                                wire:target='verify'
                                >
                            </i>
                            Verify
                        </button>
                    @else
                        <button type="button" class="btn btn-success" id='change_password_button'
                            wire:click='change_password'
                            wire:loading.attr='disabled'
                            >
                            <i id="change_password_load" class="fas fa-spinner fa-spin" 
                                wire:loading
                                wire:target='change_password'
                                >
                            </i>
                            Save
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
