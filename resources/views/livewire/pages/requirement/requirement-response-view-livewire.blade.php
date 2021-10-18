<div class="card mx-md-3 mb-5">
    <div class="card-header bg-white pb-2 border-bottom-0">
        <div class="d-flex">
            <h5 class="ml-0 mr-auto mb-1 mt-2 btn-block">
                <a href="{{ route('requirement.response', [$scholar_response->requirement_id]) }}">   
                    <div class="input-group mb-1 item-hover">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-primary text-white border-primary">
                                <i class="fas fa-file-invoice"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control bg-white border-primary rounded-right" value="View Your Response" readonly>
                    </div>     
                </a>
            </h5>
            
            @can('delete', $scholar_response)
                <div class="mr-0 ml-2 mt-2">
                    <button wire:click="delete_response_confirmation" class="btn btn-danger">
                        <i class="fas fa-minus-circle"></i>
                    </button>
                </div>
            @endcan
        </div>
    </div>
    <div class="card-body pt-0">
        <table>
            <tr>
                <td>Date Submitted:</td>
                <td class="pl-2">
                    @if ( is_null($scholar_response->submit_at) )
                        Not Yet Submitted
                    @else
                        {{ \Carbon\Carbon::parse($scholar_response->end_at)->format("M d,  Y h:i A") }}
                        @if ( is_null($scholar_response->requirement->enable) && !$scholar_response->submmited_on_time() )
                            <span class="badge badge-pill badge-danger">Late</span>
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <td>Status:</td>
                <td class="pl-2">
                    @if ( is_null($scholar_response->submit_at) )
                        <span class="badge badge-pill badge-secondary">Waiting...</span>
                    @elseif ( is_null($scholar_response->approval) )
                        <span class="badge badge-pill badge-info text-white">Pending</span>
                    @elseif ($scholar_response->approval)
                        <span class="badge badge-pill badge-success">Approved</span>
                    @else
                        <span class="badge badge-pill badge-danger">Denied</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    @if( $scholar_response->comments->count() != 0 )    
        <div class="card-footer bg-white">

            @if( is_null($scholar_response->submit_at) )  
                <div class="alert alert-info">
                    These comments will only be visible to officers once submitted.
                </div>
            @endif

            <h5>Comments</h5>
            <hr class="my-2">
            @foreach ($scholar_response->comments as $comment)

                @if ( $comment->user_id == Auth::id() ) 
                    @livewire('requirement.requirement-response-open-comment-livewire', [$comment->id], key('response-comment-open-'.time().$comment->id))

                @else
                    <div class="my-2">
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
        </div>
    @endif

    <div class="card-footer bg-white" id="requirement-response-comment">
        @livewire('requirement.requirement-response-comment-livewire', [$scholar_response->id], key('response-comment-'.time().$scholar_response->id))
    </div>
</div>