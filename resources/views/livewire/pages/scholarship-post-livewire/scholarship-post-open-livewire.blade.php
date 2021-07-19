<div>
    <div class="row mt-1 p-1">
        <div class="card col-12 bg-secondary text-white border-secondary">
            <h2 class="m-2 row">
                <strong class="my-auto">
                    {{ $scholarship->scholarship }} -  Post
                </strong>
                
                <div class="mr-1 ml-auto">
                    <a class="btn btn-light"
                        href="{{ route('scholarship.program', [$scholarship->id, 'home']) }}">
                        <i class="fas fa-newspaper"></i>
                        <strong>Home</strong>
                    </a>
                    <a class="btn btn-light"
                        href="{{ route('scholarship.program', [$scholarship->id, 'scholar']) }}">
                        <i class="fas fa-user-graduate"></i>
                        <strong>Scholars</strong>
                    </a>
                    <a class="btn btn-light"
                        href="{{ route('scholarship.program', [$scholarship->id, 'officer']) }}">
                        <i class="fas fa-address-card"></i>
                        <strong>Officers</strong>
                    </a>
                    @if (Auth::user()->usertype != 'scholar')
                        <a class="btn btn-light"
                            href="{{ route('scholarship.program', [$scholarship->id, 'requirement']) }}">
                            <i class="fas fa-file-alt"></i>
                            <strong>Requirements</strong>
                        </a>
                    @endif
                </div>
            </h2>
        </div>
    </div>

    <hr>
    <div class="row">
        <div class="col-12 mb-2">
            <div class="card mb-3 shadow item-hover mx-auto" style="max-width: 800px">
                <div class="card-header"> 
                    <h5 class="d-flex">
                        {{ $post->title }} 
                        <div class="dropdown mr-0 ml-auto">
                            <span id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-h"></i>
                            </span>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a wire:click="delete_post_confirmation" class="dropdown-item">
                                    <i class="fas fa-trash mr-1"></i>
                                    Delete Post
                                </a>
                            </div>
                        </div>
                    </h5>
                    <div class="d-flex">
                        <div class="mr-auto bd-highlight my-0">
                            <h6 class="my-0">
                                {{ $post->firstname }} {{ $post->lastname }}
                            </h6>
                        </div>
                        <h6 class="ml-auto mr-1 bd-highlight my-0">
                            {{ $post->created_at }}
                        </h6>
                    </div>
                </div>
                <div class="card-body bg-light">
                    <p class="mb-0">
                        {!! nl2br(e($post->post)) !!}
                    </p>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <a>
                        Comments 
                        <span class="badge badge-primary">{{ $comment_count }}</span>
                    </a>
                </div>
            </div>

            @livewire('scholarship-post-comment-livewire', [$post->id], key('scholarship-page-post-comment-'.time().$post->id))

            @foreach ($comments as $comment)
                @livewire('scholarship-post-open-comment-livewire', [$comment->id], key('scholarship-page-post-comment-open-'.time().$post->id))
            @endforeach
            
            @if ($show_more)
                <div class="card mb-3 shadow requirement-item-hover mx-auto mb-5" style="max-width: 760px">
                    <div class="card-header">
                        <button class="btn btn-block btn-primary"
                            wire:click="load_more"
                            wire:loading.remove
                            wire:target="load_more"
                            >
                            Load more
                        </button>
                        <div wire:loading wire:target="load_more">
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        </div>  
                    </div>
                </div>
            @else
                <hr class="mb-5" style="max-width: 760px">
            @endif
        </div>
    </div>

    
    <script>
        $(".item-hover").hover(function () {
            $(this).toggleClass("shadow-lg");
        });

        window.addEventListener('swal:confirm:delete_post_{{ $post->id }}', event => { 
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