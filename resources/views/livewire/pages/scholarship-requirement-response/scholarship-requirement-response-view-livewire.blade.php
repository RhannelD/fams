<div>   
@isset($scholar_response)
    <div class="table-responsive">
        <div class="row mx-1">
            <div class="col-md-4 order-md-last">
                <div class="card mt-2 shadow
                    @if ( is_null($scholar_response->approval) && isset($scholar_response->unit) && isset($scholar_response->gwa) )
                        @if ( $classifier_knn->predict([$scholar_response->gwa->gwa, $scholar_response->unit->units]) == 'approve' )
                            border-success
                        @else
                            border-danger
                        @endif
                    @endif
                    "
                    >
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
                    <div class="card-body" wire:poll.8000ms>
                        @isset($scholar_response->comments)
                            <h5 class="mx-2">Comments</h5>
                            <hr class="my-2">
                            @foreach ($scholar_response->comments as $comment)
                                @if ( $comment->user_id == Auth::id() ) 
                                    @livewire('requirement.requirement-response-open-comment-livewire', [$comment->id], key('response-comment-open-'.time().$comment->id))
                
                                @else
                                    <div class="my-1">
                                        <div class="mr-auto mx-2 p-0 bd-highlight d-flex">
                                            <h6>
                                                <strong> {{ $comment->user->flname() }} </strong>
                                            </h6>
                            
                                            <h6 class="ml-auto mr-1 bd-highlight my-0">
                                                {{ \Carbon\Carbon::parse($comment->created_at)->format("M d, Y h:i A") }}
                                            </h6>
                                        </div>
                                        <p class="mb-0 mx-2">{!! nl2br(e($comment->comment)) !!}</p>
                                    </div>
                                    
                                    <hr class="my-1">
                                @endif
                            @endforeach
                        @endisset
                    </div>
                    <div wire:ignore class="card-footer bg-white" id="requirement-response-comment">
                        @livewire('requirement.requirement-response-comment-livewire', [$response_id], key('response-comment-'.time().$response_id))
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                
                <div class="card mb-3 mt-2 shadow requirement-item-hover">
                    <div class="card-body">
                        <h4 class="ml-0">
                            <a data-toggle="collapse" href="#collapse-scholar-info" role="button" aria-expanded="false" aria-controls="collapse-scholar-info">
                                {{ $scholar_response->user->flname() }}
                            </a>
                        </h4>
                        <table>
                            <tr>
                                <td>Submitted at:</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($scholar_response->submit_at)->format("M d,  Y h:i A") }}     
                                    @if ( is_null($scholar_response->requirement->enable) && !$scholar_response->submmited_on_time() )
                                        <span class="badge badge-pill badge-danger">Late</span>
                                    @endif
                                </td>
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
                        <div class="collapse" id="collapse-scholar-info" wire:ignore.self>
                            <div class="card card-body py-2">
                                @include('livewire.pages.scholarship-requirement-response.scholarship-requirement-response-view-scholar-livewire')
                            </div>
                        </div>
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
                                    @isset($requirement_item->response_files[0])
                                        @php
                                            $response_file = $requirement_item->response_files[0];
                                        @endphp
                                        <div class="d-flex">
                                            <div class="mr-1 bd-highlight my-0 btn-block">
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
                                                    <div class="form-control text-nowrap overflow-auto bg-white border-primary rounded-right mr-1">
                                                        <a href="{{ route('file.preview', [$response_file->id]) }}" target="blank" class="text-dark">
                                                            {{ $response_file->file_name }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            @if (isset($file_extension) && $is_desktop)   
                                                <button class="btn btn-primary rounded" type="button" data-toggle="collapse" 
                                                    data-target="#collapsefile_{{ $response_file->id }}" 
                                                    aria-expanded="false" aria-controls="collapsefile_{{ $response_file->id }}">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                            @endif
                                        </div>
                                        
                                        @if (isset($file_extension) && $is_desktop && $file_extension == 'pdf')
                                            <div wire:ignore.self class="collapse"  id="collapsefile_{{ $response_file->id }}">
                                                <hr class="mb-1 mt-2">
                                                <iframe src="{{ Storage::disk('files')->url($response_file->file_url) }}" frameborder="0"
                                                    class="btn-block" style="height: 800px;">
                                                </iframe>
                                            </div>
                                        @endif
                                    @endisset
                                    @break

                                @case('question')  
                                @case('gwa')  
                                @case('units')  
                                    <div class="border-primary card">
                                        <p class="mb-0 card-body py-2 px-3">
                                            @if( $requirement_item->type == 'question' && isset($requirement_item->response_answer[0]) )
                                                {!! nl2br(e($requirement_item->response_answer[0]->answer)) !!}
                                            @elseif ( $requirement_item->type == 'units' && isset($requirement_item->response_units[0]) )
                                                {!! nl2br(e($requirement_item->response_units[0]->units)) !!}
                                            @elseif ( $requirement_item->type == 'gwa' && isset($requirement_item->response_gwas[0]) )
                                                {!! nl2br(e(number_format((float)$requirement_item->response_gwas[0]->gwa, 2, '.', ''))) !!}
                                            @endif
                                        </p>
                                    </div>
                                    @break

                                @case('radio')    
                                    @foreach ($requirement_item->options as $option)
                                        @if ( isset($option->responses[0]) )
                                            <div class="input-group mb-1">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text border-primary bg-primary text-white">
                                                        <i class="fas fa-check-circle"></i>
                                                    </div>
                                                </div>
                                                <div class="form-control text-nowrap overflow-auto">
                                                    {{ $option->option }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="input-group mb-1">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="far fa-circle"></i>
                                                    </div>
                                                </div>
                                                <div class="form-control text-nowrap overflow-auto">
                                                    {{ $option->option }}
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                    @break

                                @case('check')
                                    @foreach ($requirement_item->options as $option)
                                        @if ( isset($option->responses[0]) )
                                            <div class="input-group mb-1">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text border-primary bg-primary text-white">
                                                        <i class="fas fa-check-square"></i>
                                                    </div>
                                                </div>
                                                <div class="form-control text-nowrap overflow-auto">
                                                    {{ $option->option }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="input-group mb-1">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="far fa-square"></i>
                                                    </div>
                                                </div>
                                                <div class="form-control text-nowrap overflow-auto">
                                                    {{ $option->option }}
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                    @break
                            @endswitch
                    
                        </div>
                    </div>
                @endforeach
                
                @isset ( $scholar_response->requirement->agreements[0] )
                    <div class="card mb-3 shadow requirement-item-hover">
                        <div class="card-body">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text border-primary bg-primary text-white">
                                        @if ( isset($scholar_response->requirement->agreements[0]->response_agreements[0]) )
                                            <i class="fas fa-check-square"></i>
                                        @else
                                            <i class="far fa-square"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-control text-nowrap overflow-auto">
                                    I agree with the 
                                    <a data-toggle="collapse" href="#agreement-collapse" role="button" aria-expanded="false" aria-controls="agreement-collapse">
                                        Terms and Conditions
                                    </a>
                                </div>
                            </div>
                            <div wire:ignore.self class="collapse" id="agreement-collapse">
                                <hr class="my-2">
                                <p>
                                    {!! Purify::clean($scholar_response->requirement->agreements[0]->agreement) !!}
                                </p>
                            </div>
                        </div>
                    </div>
                @endisset
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

        window.addEventListener('swal:confirm:approve', event => { 
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
