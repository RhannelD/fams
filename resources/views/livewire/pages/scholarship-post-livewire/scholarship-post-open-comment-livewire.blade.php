<div class="div_comment_id_{{ $comment->id }} shadow p-2 mx-2 bg-white rounded bd-highlight item-hover mx-auto mb-3" style="max-width: 760px">
    <div class="mr-auto mx-2 p-0 bd-highlight d-flex">
        <h6>
            <strong> {{ $comment->firstname }} {{ $comment->lastname }} </strong>
        </h6>
        
        @if (Auth::id() == $comment->user_id)
            <div class="dropdown mr-0 ml-auto">
                <span id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-h"></i>
                </span>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a wire:click="delete_comment_confirmation" class="dropdown-item">
                        <i class="fas fa-trash mr-1"></i>
                        Delete Comment
                    </a>
                </div>
            </div>
        @endif
    </div>
    <p class="mb-0 mx-2">{!! nl2br(e($comment->comment)) !!}</p>

    
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
</div>
