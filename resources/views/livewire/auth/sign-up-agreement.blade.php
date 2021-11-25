<div>
    <hr class="my-2">
    <div>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input wire:model="agreeement" type="checkbox" aria-label="Checkbox for following text input">
                </div>
            </div>
            <div class="form-control">
                I agree with the
                <a href="" type="button" data-toggle="modal" data-target="#data-privacy-modal">
                    Data Policy
                </a>
            </div>
        </div>
        @error('agreeement') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div wire:ignore.self class="modal fade" id="data-privacy-modal" tabindex="-1" aria-labelledby="data-privacy-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="data-privacy-modal-label">Data Policy</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>
                        Privacy Statement
                    </h3>
                    <p>
                        Educational Assistance Management System is committed on protecting your privacy. 
                        This privacy Statement applies to your personal information. 
                        It does not apply to other system, sites, products or services.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
