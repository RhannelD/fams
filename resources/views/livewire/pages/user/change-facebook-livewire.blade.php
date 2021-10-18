<div>
    <div wire:ignore.self class="modal fade" id="update-facebook-modal" tabindex="-1" aria-labelledby="update-email-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="update-email-modal-label">
                        <strong>Update Facebook</strong>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="c_middlename">Facebook Link</label>
                        <input type="text" wire:model.lazy="facebook_link" class="form-control" id="c_middlename" placeholder="Facebook Link">
                        @error('facebook_link') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
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
                </div>
            </div>
        </div>
    </div>
</div>
