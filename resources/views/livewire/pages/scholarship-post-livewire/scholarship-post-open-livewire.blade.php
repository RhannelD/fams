<div>
@if ( isset($post) )
    <div class="row mt-1 p-1">
        <div class="card col-12 bg-secondary text-white border-secondary">
            <h2 class="m-2 row">
                <strong class="my-auto">
                    {{ $post->scholarship->scholarship }} -  Post
                </strong>
                
                <div class="mr-1 ml-auto">
                    <a class="btn btn-light"
                        href="{{ route('scholarship.program', [$post->scholarship_id, 'home']) }}">
                        <i class="fas fa-newspaper"></i>
                        <strong>Home</strong>
                    </a>
                    <a class="btn btn-light"
                        href="{{ route('scholarship.program', [$post->scholarship_id, 'scholar']) }}">
                        <i class="fas fa-user-graduate"></i>
                        <strong>Scholars</strong>
                    </a>
                    <a class="btn btn-light"
                        href="{{ route('scholarship.program', [$post->scholarship_id, 'officer']) }}">
                        <i class="fas fa-address-card"></i>
                        <strong>Officers</strong>
                    </a>
                    @if (Auth::user()->usertype != 'scholar')
                        <a class="btn btn-light"
                            href="{{ route('scholarship.program', [$post->scholarship_id, 'requirement']) }}">
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
                        @if ( $post->user_id == Auth::id() || Auth::user()->is_admin() )
                            <div class="dropdown mr-0 ml-auto">
                                <span id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h"></i>
                                </span>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a wire:click="delete_post_confirmation" class="dropdown-item">
                                        <i class="fas fa-trash mr-1"></i>
                                        Delete Post
                                    </a>
                                    <a class="dropdown-item" data-toggle="modal" data-target="#post_something">
                                        <i class="fas fa-pen-square mr-1"></i>
                                        Edit Post
                                    </a>
                                </div>
                            </div>
                        @endif
                    </h5>

                    @if ( $post->user_id == Auth::id() || Auth::user()->is_admin() )
                        @livewire('scholarship-post-livewire', [$post->scholarship_id, $post->id], key('scholarship-page-post-'.time().$post->scholarship_id))
                    @endif

                    <div class="d-flex">
                        <div class="mr-auto bd-highlight my-0">
                            <h6 class="my-0">
                                @if ( $post->user->id )
                                    {{ $post->user->flname() }}
                                @endif
                            </h6>
                        </div>
                        <h6 class="ml-auto mr-1 bd-highlight my-0">
                            {{ date('d-m-Y h:i A', strtotime($post->created_at)) }}
                        </h6>
                    </div>
                </div>
                <div class="card-body bg-light">
                    <p class="mb-3">
                        {!! nl2br(e($post->post)) !!}
                    </p>

                    @if ( count($post->requirement_links) != 0 )
                        <hr>
                        @foreach ($post->requirement_links as $requirement_link)
                            <a 
                                @if ( Auth::user()->usertype == 'scholar' )
                                    href="{{ route('requirement.view', [$requirement_link->requirement->id]) }}"
                                @else
                                    href="{{ route('scholarship.program', [ $requirement_link->requirement->scholarship_id, 'requirement', $requirement_link->requirement->id]) }}"  
                                @endif
                                >
                            <div class="input-group mb-1  item-hover">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-file-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control bg-white" value="{{ $requirement_link->requirement->requirement }}" readonly>
                                </div>
                            </a>
                        @endforeach
                        <hr class="mb-1">
                    @endif

                </div>
                <div class="card-footer d-flex justify-content-end">
                    <div class="dropdown mr-0 ml-auto">
                        <span id="dropdown_comments" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="badge badge-primary pt-1">{{ $comment_count }}</span>
                            Comments 
                            <i class="fas fa-caret-down"></i>
                        </span>
                        <div class="dropdown-menu" aria-labelledby="dropdown_comments">
                            @if ( isset($post_count) )
                                <a wire:click='view_all' class="dropdown-item">
                                    View all comments
                                </a>
                            @else
                                <a wire:click='view_latest' class="dropdown-item">
                                    View latest comments
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <hr style="max-width: 760px">

            @if ($show_more)
                <div class="card mb-3 shadow requirement-item-hover mx-auto" style="max-width: 760px">
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
            @endif
            
            @foreach ($comments as $comment)
                @livewire('scholarship-post-open-comment-livewire', [$comment->id], key('scholarship-page-post-comment-open-'.time().$post->id))
            @endforeach

            @livewire('scholarship-post-comment-livewire', [$post->id], key('scholarship-page-post-comment-'.time().$post->id))
            
            <hr class="mb-5" style="max-width: 760px">
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
        
@else
    <div class="alert alert-info mt-5 m-md-5">
        This post doesn't exist.
    </div>

@endif
</div>
