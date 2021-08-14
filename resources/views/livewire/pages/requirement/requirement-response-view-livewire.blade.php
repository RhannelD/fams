<div class="card mx-md-3 mb-5">
    <div class="card-header bg-white pb-2 border-bottom-0">
        <div class="d-flex">
            <h5 class="ml-0 mr-auto mb-1 mt-2 btn-block">
                <a href="{{ route('reponse', [$response->requirement_id]) }}">   
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
            
            @if ( !$response->cant_be_edit() )    
                <div class="mr-0 ml-2">
                    <button class="btn btn-danger">
                        <i class="fas fa-minus-circle"></i>
                    </button>
                </div>
            @endif
        </div>
    </div>
    <div class="card-body pt-0">
        <table>
            <tr>
                <td>Submitted at:</td>
                <td class="pl-2">
                    @if ( is_null($response->submit_at) )
                        Not Yet Submitted
                    @else
                        {{ date_format(new DateTime($response->submit_at),"M d,  Y h:i A") }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Approval:</td>
                <td class="pl-2">
                    @if ( is_null($response->approval) )
                        <span class="badge badge-pill badge-dark">Not Yet Submitted</span>
                    @elseif ($response->approval)
                        <span class="badge badge-pill badge-success">Approved</span>
                    @else
                        <span class="badge badge-pill badge-danger">Denied</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>
    <div class="card-footer bg-white">
        <h5>Comments</h5>
        <hr class="my-2">
        @foreach ($response_comments as $comment)
            <div class="my-2">
                <div class="mr-auto mx-2 p-0 bd-highlight d-flex">
                    <h6>
                        <strong> {{ $comment->user->firstname }} {{ $comment->user->lastname }} </strong>
                    </h6>
    
                    <h6 class="ml-auto mr-1 bd-highlight my-0">
                        {{ date('d-m-Y h:i A', strtotime($comment->created_at)) }}
                    </h6>
                    
                    @if (Auth::id() == $comment->user_id)
                        <div class="dropdown mr-0 ml-1">
                            <span id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-h"></i>
                            </span>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item">
                                    <i class="fas fa-trash mr-1"></i>
                                    Delete Comment
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                <p class="mb-0 mx-2">{!! nl2br(e($comment->comment)) !!}</p>
            </div>
            <hr class="my-1">
        @endforeach
    </div>

    <div class="card-footer bg-white">
        @livewire('requirement-response-comment-livewire', [$response->id], key('response-comment-'.time()))
    </div>
</div>