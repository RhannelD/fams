<div class="card shadow mb-2 requirement-item-hover">
@isset($requirement)
    <div class="card-body">
        <h6 class="my-1 text-center">
            {{ $requirement->promote? 'Application': 'Renewal' }} Form Access
        </h6>
        <hr class="my-2">
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="enabled" 
                wire:click="toggle_enable_form" 
                @if (!isset($requirement->enable))
                    disabled
                @endif
                @if ($requirement->enable)
                    checked
                @endif>
            <label class="custom-control-label" for="enabled">
                @if ( $requirement->enable )
                    Enabled
                @else
                    Disabled
                @endif
            </label>
        </div>
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="disable_at_end" 
                wire:click="toggle_disable_at_end"
                @if (!isset($requirement->enable))
                    checked
                @endif>
            <label class="custom-control-label" for="disable_at_end">
                Set Due Date
            </label>
        </div>
        @if ( is_null($requirement->enable) )    
            <hr class="my-2">
            <div class="form-group">
                <label for="text">Start At</label>
                <input type="datetime-local" class="form-control bg-white" id="start_at"
                    wire:model.lazy="start_at">
                @error('start_at') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="end_at">End At</label>
                <input type="datetime-local" class="form-control bg-white" id="end_at"
                    wire:model.lazy="end_at">
                @error('end_at') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        @endif

        <div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
            <div class="toast hide enable_form_toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
                <div class="toast-header">
                    <strong class="mr-3">Alert! </strong>
                    <small class="ml-auto">Just now</small>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    Toggled Form Requirement
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('toggle_enable_form', event => { 
            $('.enable_form_toast .toast-body').text(event.detail.message);
            $('.enable_form_toast').toast('show');
        });
    </script>  
@endisset  
</div>
