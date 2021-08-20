<div>
@isset($requirement)
    <hr>
    <div class="row">
        <div class="col-12 col-md-3 mb-2">

            <div class="card shadow mb-2 requirement-item-hover">
                <div class="card-body">

                    @if ( $user_response->cant_be_edit() )
                        <div class="alert alert-info mb-2">
                            You can't edit your response anymore.
                            <br>
                            @if ( $user_response->approval )
                                This has already been approved.
                            @else
                                This has already been denied.
                            @endif
                        </div>
                        <a href="{{ route('requirement.view', [$requirement->id]) }}" class="btn btn-info btn-block pr-md-4 text-white">
                            View requirement info
                        </a>
                            
                    @elseif ( !isset( $user_response->submit_at ) )
                        <button wire:click="submit_response" class="btn btn-success btn-block">
                            <i class="fas fa-paper-plane mr-1" wire:loading.remove wire:target="submit_response"></i>
                            <i class="fas fa-spinner fa-spin" wire:loading wire:target="submit_response"></i>
                            Submit Response
                        </button>

                    @else
                        <button wire:click="unsubmit_response" class="btn btn-danger btn-block">
                            <i class="fas fa-trash mr-1" wire:loading.remove wire:target="unsubmit_response"></i>
                            <i class="fas fa-spinner fa-spin" wire:loading wire:target="unsubmit_response"></i>
                            Unsubmit Response
                        </button>
                        
                    @endif

                </div>
            </div>

            @isset( $user_response->approval )     
                <div class="card shadow mb-2 requirement-item-hover">
                    <div class="card-body pb-1">
                        @if ($user_response->approval)
                            <div class="alert alert-success">
                                Approved
                            </div>
                        @else
                            <div class="alert alert-danger">
                                Denied
                            </div>
                        @endif
                    </div>
                </div>
            @endisset

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
                                        @livewire('response-file-upload-livewire', [$requirement_item->id, $user_response->id], key('response-file-livewire-'.time().$requirement_item->id))
                                        @break

                                    @case('question')
                                        @livewire('response-answer-livewire', [$requirement_item->id, $user_response->id], key('response-answer-livewire-'.time().$requirement_item->id))
                                        @break

                                    @case('radio')
                                        @livewire('response-radio-livewire', [$requirement_item->id, $user_response->id], key('response-radio-livewire-'.time().$requirement_item->id))
                                        @break

                                    @case('check')
                                        @isset($requirement_item->options)
                                            @foreach ($requirement_item->options as $option)
                                                @livewire('response-checkbox-livewire', [$requirement_item->id, $user_response->id, $option->id], key('response-checkbox-livewire-'.time().$requirement_item->id.'_'.$option->id))
                                            @endforeach
                                        @endisset
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
@endisset
</div>
