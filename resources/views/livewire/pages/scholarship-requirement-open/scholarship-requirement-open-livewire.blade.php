<div>
@isset($requirement)
    @livewire('add-ins.scholarship-program-livewire', [$requirement->scholarship_id, 'requirements'], key('page-tabs-'.time().$requirement->scholarship_id))

    <div class="mx-auto mt-2 mxw-1300px">
        <div class="row mx-2">
            <div class="col-12 col-md-3 mb-2">

                <div class="card shadow mb-2 requirement-item-hover">
                    <div class="card-body">
                        @if ( isset($requirement->categories->first()->category) )
                            <h5>Category</h5>
                            <table>
                                <tr>
                                    <td>Name:</td>
                                    <td>{{ $requirement->categories->first()->category->category }}</td>
                                </tr>
                                <tr>
                                    <td>Amount:</td>
                                    <td>Php {{ $requirement->categories->first()->category->amount }}</td>
                                </tr>
                                <tr>
                                    <td>For:</td>
                                    <td>{{ $requirement->promote? 'Applicatants': 'Old Scholars' }}</td>
                                </tr>
                            </table>
                        @else
                            <div class="alert alert-info my-auto">
                                The requirement’s category is not set!
                            </div>
                        @endif
                    </div>
                </div>
                    
                @livewire('scholarship-requirement-edit.scholarship-requirement-activate-livewire', [$requirement->id], key('activate-livewire-'.time().$requirement->id))

                <div class="card shadow mb-2 requirement-item-hover">
                    <div class="card-body">
                        <a href="{{ route('scholarship.requirement.responses', [$requirement->id]) }}" class="btn btn-success btn-block">
                            View Responses
                            <span class="badge badge-dark">
                                <strong>
                                    {{ $requirement->responses->whereNotNull('submit_at')->count() }}
                                </strong>
                                @if ( !$requirement->promote && $requirement->categories->count() > 0 )
                                    /{{ $requirement->categories->first()->category->scholars->count() }}
                                @endif
                            </span>
                        </a>
                    </div>
                </div>

                <div class="card shadow requirement-item-hover">
                    <div class="card-body">
                        @if ( $requirement->get_submitted_responses_count() )   
                            <div class="alert alert-danger">
                                This requirement has scholars' responses.
                            </div>
                            <button wire:click='edit_confirm' class="btn btn-info btn-block text-white">
                                Edit
                            </button>
                        @else
                            <a href="{{ route('scholarship.requirement.edit', [$requirement->id]) }}" class="btn btn-info btn-block text-white">Edit</a>
                        @endif
                        <button wire:click="delete_confirmation"  class="btn btn-danger btn-block">Delete</button>
                    </div>
                </div>

                <hr>
            </div>

            <div class="col-12 col-md-9 order-md-first">
                <div class="card border-primary mb-4 shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="my-auto">
                            @isset( $requirement->requirement )
                                <strong>{{ $requirement->requirement }}</strong>
                            @endisset
                        </h2>
                    </div>
                    <div class="card-body border-primary">
                        <p class="mb-0">
                            @isset( $requirement->description )
                                {!! Purify::clean($requirement->description) !!}
                            @endisset
                        </p>
                    </div>
                </div>
            
                <div class="row">
                    <div class="col-sm-12 offset-sm-0 col-md-10 offset-md-1">
                        @isset($requirement->items)
                        @foreach ($requirement->items as $requirement_item)
                            <div class="card mb-3 shadow requirement-item-hover">
                                <div class="card-body">
            
                                    <h4>{{ $requirement_item->item }}</h4>
                                    @if (!empty($requirement_item->note))
                                        <p>{{ $requirement_item->note }}</p>
                                    @endif
            
                                    @switch($requirement_item->type)
                                        @case('file')
                                        @case('cor')
                                        @case('grade')
                                            <div class="input-group mb-1">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-file"></i>
                                                    </span>
                                                </div>
                                                <div class="form-control">
                                                    @if($requirement_item->type == 'grade')
                                                        Grade Upload
                                                    @elseif ($requirement_item->type == 'cor')
                                                        COR Upload
                                                    @else
                                                        File Upload
                                                    @endif
                                                </div>
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
                                                            <i class="far fa-circle"></i>
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
                                                            <i class="far fa-square"></i>
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
                        @endisset

                        @if ( $requirement->agreements->count() )
                            <div class="card mb-3 shadow requirement-item-hover">
                                <div class="card-body">
                                    <h4>Terms and Conditions</h4>
                                    <hr class="my-2">
                                    <p>
                                        {!! Purify::clean($requirement->agreements->first()->agreement) !!}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(".requirement-item-hover").hover(function () {
            $(this).toggleClass("shadow-lg");
        });
        
        window.addEventListener('swal:confirm:delete_requirement', event => { 
            swal({
              title: event.detail.message,
              text: event.detail.text,
              icon: event.detail.type,
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                @this.call(event.detail.function)
              }
            });
        });

        window.addEventListener('swal:confirm:edit_requirement', event => { 
            swal({
              title: event.detail.message,
              text: event.detail.text,
              icon: event.detail.type,
              buttons: true,
              dangerMode: false,
            })
            .then((willDelete) => {
              if (willDelete) {
                @this.call(event.detail.function)
              }
            });
        });
    </script>
@endisset
</div>
