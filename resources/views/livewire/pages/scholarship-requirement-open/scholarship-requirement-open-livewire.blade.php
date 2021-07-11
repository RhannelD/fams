<div>
    <div class="card bg-primary border-primary mb-4">
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

    
    <script>
        $(".requirement-item-hover").hover(function () {
            $(this).toggleClass("shadow-lg bg-light");
        });
    </script>
</div>
