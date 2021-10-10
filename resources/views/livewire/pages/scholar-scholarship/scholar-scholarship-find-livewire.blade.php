<div>
    <div class="row mt-3">
        <div class="col-12">
            @foreach ($posts as $post)
                <div class="card mb-3 shadow requirement-item-hover mx-auto" style="max-width: 800px">
                    <div class="card-header"> 
                        <h5>
                            <a href="{{ route('post.show', [$post->id]) }}" class="text-dark">
                                {{ $post->title }} {{ $post->id }}
                            </a>
                        </h5>
                        <div class="d-flex">
                            <div class="mr-auto bd-highlight my-0">
                                @isset($post->user)
                                    <h6 class="my-0">
                                        {{ $post->scholarship->scholarship }}
                                    </h6>
                                @endisset
                            </div>
                            <h6 class="ml-auto mr-1 bd-highlight my-0">
                                {{ date('d-m-Y h:i A', strtotime($post->created_at)) }}
                            </h6>
                        </div>
                    </div>
                    <a href="{{ route('post.show', [$post->id]) }}" class="nounderline text-dark">
                        <div class="card-body bg-light">
                            <p class="mb-0">
                                {!! Purify::clean($post->post) !!}
                            </p>

                            @if ( $post->requirement_links->count() > 0 )
                                <h6 class="mt-2">
                                    <i class="fas fa-link"></i>
                                    Requirement links: 
                                    <span class="badge badge-primary mr-2 pt-1">{{ $post->requirement_links->count() }}</span>
                                </h6>
                            @endif
                        </div>
                    </a>
                    <div class="card-footer d-flex justify-content-end">
                        <a href="{{ route('post.show', [$post->id]) }}" class=" text-dark">
                            Comment
                            <span class="badge badge-primary pt-1">{{ $post->comments->count() }}</span>
                        </a>
                    </div>
                </div>
            @endforeach
            @if ($show_more)
                <div class="card mb-3 shadow requirement-item-hover mx-auto" style="max-width: 800px">
                    <div class="card-header">
                        <button class="btn btn-block btn-primary"
                            wire:click="load_more"
                            wire:loading.remove
                            wire:target="load_more"
                            >
                            Load more
                        </button>
                        <div wire:loading wire:target="load_more">
                            <span class="spinner-grow spinner-grow-sm mx-1" role="status" aria-hidden="true"></span>
                            <span class="spinner-grow spinner-grow-sm mx-1" role="status" aria-hidden="true"></span>
                            <span class="spinner-grow spinner-grow-sm mx-1" role="status" aria-hidden="true"></span>
                        </div>  
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
