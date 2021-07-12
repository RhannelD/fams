<div>
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
                    <button class="btn btn-success btn-block">View Responds</button>
                </div>
            </div>

            <div class="card shadow requirement-item-hover">
                <div class="card-body">
                    <button class="btn btn-info btn-block text-white">Edit</button>
                    <button class="btn btn-danger btn-block">Delete</button>
                </div>
            </div>

            <hr>
        </div>

        <div class="col-12 col-md-9 order-md-first">
            <div class="card bg-primary border-primary mb-4 shadow">
                <div class="card-body text-white border-primary">
                    <h2>
                        <strong>{{ $requirement->requirement }}</strong>
                    </h2>
                    <p class="mb-0">
                        {{ $requirement->description }}
                    </p>
                </div>
            </div>
        
            <div class="row">
                <div class="col-sm-12 offset-sm-0 col-md-10 offset-md-1">
                    @foreach ($requirement_items as $requirement_item)
                        <div class="card mb-3 shadow requirement-item-hover">
                            <div class="card-body">
        
                                <h4>{{ $requirement_item->item }}</h4>
                                @if (!empty($requirement_item->note))
                                    <p>{{ $requirement_item->note }}</p>
                                @endif
        
                                @switch($requirement_item->type)
                                    @case('file')
                                        <div class="input-group mb-1">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-file"></i>
                                                </span>
                                            </div>
                                            <div class="form-control">File Upload</div>
                                        </div>
                                        @break
                                    @case('question')
                                        <div class="input-group mb-1">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-question"></i>
                                                </span>
                                            </div>
                                            <div class="form-control">Answer</div>
                                        </div>
                                        @break
                                    @case('radio')
                                        @foreach ($requirement_item->options as $option)
                                            <div class="input-group mb-1">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input type="radio" name="radio_{{ $requirement_item->id }}">
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control bg-white" value="{{ $option->option }}" readonly>
                                            </div>
                                        @endforeach
                                        @break
                                    @case('check')
                                        @foreach ($requirement_item->options as $option)
                                            <div class="input-group mb-1">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input type="checkbox">
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control bg-white" value="{{ $option->option }}" readonly>
                                            </div>
                                        @endforeach
                                        @break
                                @endswitch
                            </div>
                        </div>
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
