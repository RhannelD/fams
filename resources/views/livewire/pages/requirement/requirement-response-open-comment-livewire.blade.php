<div class="div_comment_id_{{ $comment_id }}">
@isset($comment)

    <div class="my-2">
        <div class="mr-auto mx-2 p-0 bd-highlight d-flex">
            <h6>
                <strong> {{ $comment->user->firstname }} {{ $comment->user->lastname }} </strong>
            </h6>
    
            <h6 class="ml-auto mr-1 bd-highlight my-0">
                {{ \Carbon\Carbon::parse($comment->created_at)->format("M d, Y h:i A") }}
            </h6>
            
            @can('delete', $comment)
                <div wire:ignore.self class="dropdown mr-0 ml-1">
                    <span id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-h"></i>
                    </span>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a wire:click='delete_comment_confirmation' class="dropdown-item">
                            <i class="fas fa-trash mr-1"></i>
                            Delete Comment
                        </a>
                    </div>
                </div>
            @endcan
        </div>
        <p class="mb-0 mx-2">{!! nl2br(e($comment->comment)) !!}</p>
       
    </div>
    <hr class="my-1">

    <script>
        window.addEventListener('delete_comment_div', event => { 
            $( '.div_comment_id_'+event.detail.div_class ).fadeOut( 500, function(){
                $( '.div_comment_id_'+event.detail.div_class ).remove();
            });
        });

        window.addEventListener('swal:confirm:delete_comment_{{ $comment->id }}', event => { 
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
