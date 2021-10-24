<div>
    <div wire:ignore.self class="modal fade" id="update-personal-info" tabindex="-1" aria-labelledby="update-personal-info-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="update-personal-info-label">
                        <strong>Update Personal Information</strong>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="c_religion">Religion</label>
                        <input type="text" wire:model.lazy="user.religion" class="form-control" id="c_religion" placeholder="Religion">
                        @error('user.religion') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label for="c_address">Address</label>
                        <input type="text" wire:model.lazy="user.address" class="form-control" id="c_address" placeholder="Address">
                        @error('user.address') <span class="text-danger">{{ $message }}</span> @enderror	
                    </div>
                    <div class="form-group">
                        <label for="c_phone">Phone Number</label>
                        <input type="text" wire:model.lazy="user.phone" class="form-control" id="c_phone" placeholder="09*********">
                        @error('user.phone') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id='save-update-info'
                        wire:click='save'
                        wire:loading.attr='disabled'
                        >
                        <i id="save-update-info-load" class="fas fa-spinner fa-spin" 
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
