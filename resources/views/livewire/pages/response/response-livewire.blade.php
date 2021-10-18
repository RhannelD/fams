<div>
@isset($requirement)
    @livewire('add-ins.scholarship-program-livewire', [$requirement->scholarship_id], key('page-tabs-'.time().$requirement->scholarship_id))
    <hr>
    <div class="row">
        <div class="col-12 col-md-3 mb-2">

            <div class="card shadow mb-2 requirement-item-hover">
                <div class="card-body">
                    <a href="{{ route('requirement.view', [$requirement->id]) }}" class="btn btn-secondary btn-block text-white">
                        Requirement Preview
                    </a>
                </div>
            </div> 

            <div class="card shadow mb-2 requirement-item-hover">
                <div class="card-body">

                    @if ( $user_response->cant_be_edit() )
                        @if ($user_response->approval)
                            <div class="alert alert-success my-auto">
                                Your response had been approved.
                            </div>
                        @else
                            <div class="alert alert-danger my-auto">
                                Your response had been denied.
                            </div>
                        @endif
                            
                    @elseif ( !isset( $user_response->submit_at ) )
                        @if ( Auth::user()->can('submit', $user_response) )
                            <button wire:click="submit_response" class="btn btn-success btn-block">
                                <i class="fas fa-paper-plane mr-1" wire:loading.remove wire:target="submit_response"></i>
                                <i class="fas fa-spinner fa-spin" wire:loading wire:target="submit_response"></i>
                                Submit Response
                            </button>
                        @else
                            <div class="alert alert-danger my-auto">
                                The form is disabled.<br>
                                You cant submit this anymore.
                            </div>
                        @endif

                    @else
                        @if ( Auth::user()->can('unsubmit', $user_response) )
                            @if ( !$user_response->submmited_on_time() )
                                <div class="alert alert-danger">
                                    Submitted Late
                                </div>
                            @elseif ( $user_response->is_late_to_submit() )
                                <div class="alert alert-danger">
                                    This will be marked as late. <br>
                                    Due: {{ \Carbon\Carbon::parse($requirement->end_at)->format("M d,  Y h:i A") }}
                                </div>
                            @endif
                            <button wire:click="unsubmit_response" class="btn btn-danger btn-block">
                                <i class="fas fa-trash mr-1" wire:loading.remove wire:target="unsubmit_response"></i>
                                <i class="fas fa-spinner fa-spin" wire:loading wire:target="unsubmit_response"></i>
                                Unsubmit Response
                            </button>
                        @else
                            <div class="alert alert-info my-auto">
                                The form is disabled.<br>
                                You cant unsubmit this anymore.
                            </div>
                        @endif
                    @endif

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
                                        @livewire('response.response-file-upload-livewire', [$requirement_item->id, $user_response->id], key('response-file-livewire-'.time().$requirement_item->id))
                                        @break

                                    @case('question')
                                        @livewire('response.response-answer-livewire', [$requirement_item->id, $user_response->id], key('response-answer-livewire-'.time().$requirement_item->id))
                                        @break

                                    @case('radio')
                                        @livewire('response.response-radio-livewire', [$requirement_item->id, $user_response->id], key('response-radio-livewire-'.time().$requirement_item->id))
                                        @break

                                    @case('check')
                                        @isset($requirement_item->options)
                                            @foreach ($requirement_item->options as $option)
                                                @livewire('response.response-checkbox-livewire', [$requirement_item->id, $user_response->id, $option->id], key('response-checkbox-livewire-'.time().$requirement_item->id.'_'.$option->id))
                                            @endforeach
                                        @endisset
                                        @break
                                @endswitch

                            </div>
                        </div>
                    @endforeach
                    @if($requirement->agreements->count())
                        @livewire('response.response-agreement-livewire', [$requirement->agreements->first()->id, $user_response->id], key('response-agreement-'.time()))
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        $(".requirement-item-hover").hover(function () {
            $(this).toggleClass("shadow-lg");
        });
        
        window.addEventListener('swal:confirm:unsubmit_response', event => { 
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
