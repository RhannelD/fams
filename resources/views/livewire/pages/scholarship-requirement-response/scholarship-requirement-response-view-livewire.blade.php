<div>   
@isset($scholar_response)
    <div class="table-responsive">
        <div class="row mx-1">
            <div class="col-md-4 order-md-last">
                <div class="card mt-2 shadow">
                    <div class="card-body">
                        @if ( is_null($scholar_response->approval) )
                            <button wire:click="response_approve" class="btn btn-success">
                                Approve
                            </button>
                            <button wire:click="response_deny" class="btn btn-danger">
                                Deny
                            </button>
                            
                        @elseif ( $scholar_response->approval )
                            <div class="alert alert-success my-0 d-flex">
                                <span class="my-auto">Scholar Response Approve!</span>
                                <button wire:click="response_delete_confirm" class="btn btn-sm btn-danger my-1 rounded mr-0 ml-auto">
                                    <i class="fas fa-minus-circle"></i>
                                </button>
                            </div>  

                        @else
                            <div class="alert alert-danger my-0 d-flex">
                                <span class="my-auto">Scholar Response Denied!</span>
                                <button wire:click="response_delete_confirm" class="btn btn-sm btn-danger my-1 rounded mr-0 ml-auto">
                                    <i class="fas fa-minus-circle"></i>
                                </button>
                            </div> 

                        @endif
                    </div>
                </div>
                
                <div class="card mt-2 shadow">
                    <div class="card-body">
                        @isset($scholar_response->comments)
                        <h5 class="mx-2">Comments</h5>
                        <hr class="my-2">
                        @foreach ($scholar_response->comments as $comment)
                            @if ( $comment->user_id == Auth::id() ) 
                                @livewire('requirement-response-open-comment-livewire', [$comment->id], key('response-comment-open-'.time().$comment->id))
            
                            @else
                                <div class="my-1">
                                    <div class="mr-auto mx-2 p-0 bd-highlight d-flex">
                                        <h6>
                                            <strong> {{ $comment->user->flname() }} </strong>
                                        </h6>
                        
                                        <h6 class="ml-auto mr-1 bd-highlight my-0">
                                            {{ date('d-m-Y h:i A', strtotime($comment->created_at)) }}
                                        </h6>
                                    </div>
                                    <p class="mb-0 mx-2">{!! nl2br(e($comment->comment)) !!}</p>
                                </div>
                                
                                <hr class="my-1">
                            @endif
                        @endforeach
                        @endisset
                    </div>
                    <div class="card-footer bg-white">
                        @livewire('requirement-response-comment-livewire', [$response_id], key('response-comment-'.time().$response_id))
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                
                <div class="card mb-3 mt-2 shadow requirement-item-hover">
                    <div class="card-body">
                        <h4>{{ $scholar_response->user->flname() }}</h4>
                        <table>
                            <tr>
                                <td>Submitted at:</td>
                                <td>{{ $scholar_response->submit_at }}</td>
                            </tr>
                            <tr>
                                <td>Approval:</td>
                                <td>             
                                    @if ( is_null($scholar_response->approval) )
                                        <span class="badge badge-pill badge-info text-white">Pending</span>
                                    @elseif ($scholar_response->approval)
                                        <span class="badge badge-pill badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-pill badge-danger">Denied</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </div>

                @php
                    $is_desktop = new \Jenssegers\Agent\Agent();
                    $is_desktop = $is_desktop->isDesktop();
                @endphp 

                @foreach ($scholar_response->requirement->items as $requirement_item)
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
                                    @php
                                        $response_file = $requirement_item->response_files->where('response_id', $scholar_response->id)->first();
                                    @endphp
                                    @isset($response_file)
                                        <div class="d-flex">
                                            <div class="mr-1 bd-highlight my-0 btn-block">
                                                <a href="{{ Storage::disk('files')->url($response_file->file_url) }}" target="blank">
                                                    <div class="input-group item-hover">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-primary text-white border-primary">
                                                                @if ( $response_file->if_file_exist() )
                                                                    @php
                                                                        $file_extension = $response_file->get_file_extension();
                                                                    @endphp
                                                                    @include('livewire.pages.response.response-file-upload-icon-type-livewire')
                                                                @else
                                                                    <i class="fas fa-exclamation-circle"></i>
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <input type="text" class="form-control bg-white border-primary rounded-right mr-1" 
                                                            value="{{ $response_file->file_name }}" readonly>
                                                    </div>
                                                </a>
                                            </div>
                                            
                                            @if (isset($file_extension) && $is_desktop)   
                                                <button class="btn btn-primary rounded" type="button" data-toggle="collapse" 
                                                    data-target="#collapsefile{{ $response_file->id }}" 
                                                    aria-expanded="false" aria-controls="collapsefile{{ $response_file->id }}">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                            @endif
                                        </div>
                                        
                                        @if (isset($file_extension) && $is_desktop && $file_extension == 'pdf')
                                            <div class="collapse"  id="collapsefile{{ $response_file->id }}">
                                                <hr class="mb-1 mt-2">
                                                <iframe src="{{ Storage::disk('files')->url($response_file->file_url) }}" frameborder="0"
                                                    class="btn-block" style="height: 800px;">
                                                </iframe>
                                            </div>
                                        @endif
                                    @endisset
                                    @break

                                @case('question')  
                                    <div class="border-primary card">
                                        <p class="mb-0 card-body py-2 px-3">
                                            {!! nl2br(e($requirement_item->response_answer->where('response_id', $scholar_response->id)->first()->answer)) !!}
                                        </p>
                                    </div>
                                    @break

                                @case('radio')    
                                    @foreach ($requirement_item->options as $option)
                                        @if ( $option->responses->where('response_id', $scholar_response->id)->first() )
                                            <div class="input-group mb-1">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text border-primary bg-primary text-white">
                                                        <i class="fas fa-check-circle"></i>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control bg-white border-primary" value="{{ $option->option }}" readonly>
                                            </div>
                                        @else
                                            <div class="input-group mb-1">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="far fa-circle"></i>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control bg-white" value="{{ $option->option }}" readonly>
                                            </div>
                                        @endif
                                    @endforeach
                                    @break

                                @case('check')
                                    @foreach ($requirement_item->options as $option)
                                        @if ( $option->responses->where('response_id', $scholar_response->id)->first() )
                                            <div class="input-group mb-1">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text border-primary bg-primary text-white">
                                                        <i class="fas fa-check-square"></i>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control bg-white border-primary" value="{{ $option->option }}" readonly>
                                            </div>
                                        @else
                                            <div class="input-group mb-1">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="far fa-square"></i>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control bg-white" value="{{ $option->option }}" readonly>
                                            </div>
                                        @endif
                                    @endforeach
                                    @break
                            @endswitch
                    
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

    
	<script>
        window.addEventListener('swal:confirm:response_delete', event => { 
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
