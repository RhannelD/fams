<div>
    <hr>
    <div class="row">
        <div class="col-12 col-md-3 mb-2">

            <div class="card shadow mb-2 requirement-item-hover">
                <div class="card-body">
                    <button class="btn btn-success btn-block">Send Respond</button>
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
                    @foreach ($requirement_items as $requirement_item)
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

                                        @livewire('response-file-upload-livewire', [$requirement_item->id, $user_response->id], key('response-file-livewire-'.time().$requirement_item->id))

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
                                       
                                        @break
                                    @case('check')
                                       
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
</div>
