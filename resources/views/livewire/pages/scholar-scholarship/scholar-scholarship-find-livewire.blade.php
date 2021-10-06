<div>
    <div class="row mt-3">
        <div class="col-12">
            @foreach ($posts as $post)
                <div class="card mb-3 shadow requirement-item-hover mx-auto" style="max-width: 800px">
                    <a href="{{ route('post.show', [$post->id]) }}" class="nounderline text-dark">
                        <div class="card-header"> 
                            <h5>
                                {{ $post->title }} 
                            </h5>
                            <div class="d-flex">
                                <div class="mr-auto bd-highlight my-0">
                                    @isset($post->user)
                                        <h6 class="my-0">
                                            {{ $post->user->flname() }}
                                        </h6>
                                    @endisset
                                </div>
                                <h6 class="ml-auto mr-1 bd-highlight my-0">
                                    {{ date('d-m-Y h:i A', strtotime($post->created_at)) }}
                                </h6>
                            </div>
                        </div>

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

                        <div class="card-footer d-flex justify-content-end">
                            <a href="{{ route('post.show', [$post->id]) }}" class=" text-dark">
                                Comment
                                <span class="badge badge-primary pt-1">{{ $post->comments->count() }}</span>
                            </a>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
