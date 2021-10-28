<div>
@if ( isset($post) )
    <div wire:ignore>
        @livewire('add-ins.scholarship-program-livewire', [$post->scholarship_id], key('page-tabs-'.time().$post->scholarship_id))
    </div>

    <hr>
    <div class="row">
        <div class="col-12 mb-2">
            <div class="card mb-3 shadow item-hover mx-auto" style="max-width: 800px">
                <div class="card-header"> 
                    <h5 class="d-flex">
                        {{ $post->title }} 
                        @canany(['update', 'delete'], $post)
                            <div wire:ignore class="dropdown mr-0 ml-auto">
                                <span id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h"></i>
                                </span>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @can('delete', $post)
                                        <a wire:click="delete_post_confirmation" class="dropdown-item">
                                            <i class="fas fa-trash mr-1"></i>
                                            Delete Post
                                        </a>
                                    @endcan
                                    @can('update', $post)
                                        <a class="dropdown-item" data-toggle="modal" data-target="#post_something">
                                            <i class="fas fa-pen-square mr-1"></i>
                                            Edit Post
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        @endcanany
                    </h5>
                    
                    @can('update', $post)
                        <div wire:ignore id="scholarship-post-livewire">
                            @livewire('scholarship-post.scholarship-post-livewire', [$post->scholarship_id, $post->id], key('scholarship-page-post-'.time().$post->scholarship_id))
                        </div>
                    @endcan

                    <div class="d-flex">
                        <div class="mr-auto bd-highlight my-0">
                            <h6 class="my-0">
                                @if ( $post->user->id )
                                    {{ $post->user->flname() }}
                                @endif
                            </h6>
                        </div>
                        <h6 class="ml-auto mr-1 bd-highlight my-0">
                            {{ \Carbon\Carbon::parse($post->created_at)->format("M d,  Y h:i A") }}
                        </h6>
                    </div>
                </div>
                <div class="card-body bg-light">
                    <p class="mb-3">
                        {!! Purify::clean($post->post) !!}
                    </p>

                    @if ( count($post->requirement_links) != 0 )
                        <hr>
                        @foreach ($post->requirement_links as $requirement_link)
                            <a 
                                @if ( Auth::user()->usertype == 'scholar' )
                                    href="{{ route('requirement.view', [$requirement_link->requirement->id]) }}"
                                @else
                                    href="{{ route('scholarship.requirement.open', [$requirement_link->requirement->id]) }}"  
                                @endif
                                >
                            <div class="input-group mb-1  item-hover">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-file-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control bg-white" value="{{ $requirement_link->requirement->requirement }}" readonly>
                                    <div class="input-group-append">
                                        @if ( $requirement_link->requirement->can_be_accessed() == 'ongoing' )
                                            <span class="input-group-text bg-success text-white" id="basic-addon2">
                                                <i class="fas fa-toggle-on"></i>
                                            </span>
                                        @else
                                            <span class="input-group-text bg-danger text-white" id="basic-addon2">
                                                <i class="fas fa-toggle-off"></i>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                        <hr class="mb-1">
                    @endif

                </div>
                <div class="card-footer d-flex justify-content-end">
                    <div>
                        @if ($post->promote)
                            Global
                        @endif
                    </div>
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
            
            <div wire:poll.10000ms id="scholarship-post-open-comment-content">
                @foreach ($comments as $comment)
                    @livewire('scholarship-post.scholarship-post-open-comment-livewire', [$comment->id], key('scholarship-page-post-comment-open-'.time().$post->id))
                @endforeach
            </div>

            <div wire:ignore id="scholarship-page-post-comment-content">
                @livewire('scholarship-post.scholarship-post-comment-livewire', [$post->id], key('scholarship-page-post-comment-'.time().$post->id))
            </div>
            
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
