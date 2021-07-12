<div>
    <div class="row mt-1 p-1">
        <div class="card col-12 bg-secondary text-white border-secondary">
            <h2 class="m-2 row">
                <strong class="my-auto">
                    {{ $scholarship->scholarship }} -  Requirement
                </strong>
                
                <div class="mr-1 ml-auto">
                    <button class="btn btn-light">Action</button>
                    <button class="btn btn-light">Action</button>
                    <button class="btn btn-light">Action</button>
                </div>
            </h2>
        </div>
    </div>

    <hr>
    <div class="row">
        <div class="col-12 col-md-3 mb-2">
            <div class="card shadow mb-2 requirement-item-hover">
                <div class="card-body">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="enabled" 
                            wire:click="toggle_enable_form"
                            @if ($requirement->enable)
                                checked
                            @endif>
                        <label class="custom-control-label" for="enabled">Enable Form Requirement</label>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="text">Start At</label>
                        <input type="text" class="form-control bg-white" id="start_at" readonly
                            value="{{ \Carbon\Carbon::parse($requirement->start_at)->format('Y-m-d h:i A') }}">
                    </div>
                    <div class="form-group">
                        <label for="end_at">End At</label>
                        <input type="text" class="form-control bg-white" id="end_at" readonly
                            value="{{ \Carbon\Carbon::parse($requirement->end_at)->format('Y-m-d h:i A') }}">
                    </div>

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
            </div>
            
            <div class="card shadow mb-2 requirement-item-hover">
                <div class="card-body">
                    <button wire:click="save_all" class="btn btn-success btn-block">Save</button>
                </div>
            </div>

            <hr>
        </div>

        <div class="col-12 col-md-9 order-md-first">
            <div class="card bg-white border-dark mb-4 shadow">
                <div class="card-body border-primary">
                    <div class="form-group">
                        <label for="requirement"><h5><strong>Requirement Title</strong></h5></label>
                        <input wire:model="requirement.requirement" class="form-control form-control-lg" type="text" 
                            placeholder=".form-control-lg" id='requirement'>
                        @error('requirement.requirement') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-0">
                        <label for="description">Description</label>
                        <textarea wire:model="requirement.description" class="form-control" id="description" rows="3"></textarea>
                        @error('requirement.description') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>  
                </div>
            </div>
        
            <div class="row">
                <div class="col-sm-12 offset-sm-0 col-md-10 offset-md-1">
                    @foreach ($items as $item)
                    
                        @livewire('scholarship-requirement-edit-item-livewire', [$item->id], key('item-'.time().$item->id))

                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(".requirement-item-hover").hover(function () {
            $(this).toggleClass("shadow-lg");
        });

        window.addEventListener('toggle_enable_form', event => { 
            $('.enable_form_toast .toast-body').text(event.detail.message);
            $('.enable_form_toast').toast('show');
        });
    </script>
</div>
