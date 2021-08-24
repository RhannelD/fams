<div>
@isset($requirement)
    @livewire('scholarship-program-livewire', [$requirement->scholarship_id], key('page-tabs-'.time().$requirement->scholarship_id))
    <hr>
    <div class="row">
        <div class="col-12 col-md-3 mb-2">

            @isset($requirement->categories->first()->category)
                <div class="card shadow mb-2 requirement-item-hover">
                    <div class="card-body">
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
                        </table>
                    </div>
                </div>
            @endisset
                
            @isset($requirement->id)
                @livewire('scholarship-requirement-activate-livewire', [$requirement->id], key('activate-livewire-'.time().$requirement->id))
            @endisset

            <div class="card shadow mb-2 requirement-item-hover">
                <div class="card-body">
                    <a href="{{ route('requirement.response', [$requirement->id]) }}" class="btn btn-success btn-block">
                        View Responds
                    </a>
                </div>
            </div>

            <div class="card shadow requirement-item-hover">
                <div class="card-body">
                    @isset($requirement->id)
                        <a href="{{ route('requirement.edit', [$requirement->id]) }}" class="btn btn-info btn-block text-white">Edit</a>
                    @endisset
                    <button wire:click="delete_confirmation"  class="btn btn-danger btn-block">Delete</button>
                </div>
            </div>

            <hr>
        </div>

        <div class="col-12 col-md-9 order-md-first">
            <div class="card bg-primary border-primary mb-4 shadow">
                <div class="card-body text-white border-primary">
                    <h2>
                        @isset( $requirement->requirement )
                            <strong>{{ $requirement->requirement }}</strong>
                        @endisset
                    </h2>
                    <p class="mb-0">
                        @isset( $requirement->description )
                            {{ $requirement->description }}
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
                    @endisset
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
    </script>
@endisset
</div>
