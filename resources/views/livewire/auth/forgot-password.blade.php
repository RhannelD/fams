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
                        <i class="fas fa-spinner fa-spin"
                            wire:loading
                            wire:target='search'
                            >
                        </i>
                        Search
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
