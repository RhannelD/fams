<div>
    @livewire('scholarship-program-livewire', [$scholarship_id], key('page-tabs-'.time().$scholarship_id))

    <hr>
    <div class="row mt-3">
        <div class="col-12">
            @if (Auth::user()->usertype != 'scholar')
                <div class="card mb-4 shadow requirement-item-hover mx-auto" style="max-width: 800px">
                    <div class="card-header">
                        <a data-toggle="modal" data-target="#post_something">
                            <div class="input-group">
                                <input type="text" class="form-control bg-white" placeholder="Post something..." disabled>
                                <div class="input-group-append">
                                    <button class="btn btn-dark" type="button">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                @livewire('scholarship-post-livewire', [$scholarship_id], key('scholarship-page-post-'.time().$scholarship_id))
            @endif

            @foreach ($posts as $post)
                <div class="card mb-3 shadow requirement-item-hover mx-auto" style="max-width: 800px">
                    <a href="{{ route('post.show', [$post->id]) }}" class="nounderline text-dark">
                        <div class="card-header"> 
                            <h5>
                                {{ $post->title }} 
                            </h5>
                            <div class="d-flex">
                                <div class="mr-auto bd-highlight my-0">
                                    <h6 class="my-0">
                                        {{ $post->firstname }} {{ $post->lastname }}
                                    </h6>
                                </div>
                                <h6 class="ml-auto mr-1 bd-highlight my-0">
                                    {{ date('d-m-Y h:i A', strtotime($post->created_at)) }}
                                </h6>
                            </div>
                        </div>

                        <div class="card-body bg-light">
                            <p class="mb-0">
                                {{-- {!! nl2br(e($post->post)) !!} --}}
                                {!! $post->post !!}
                            </p>

                            @if ( $post->link_count > 0 )
                                <h6 class="mt-2">
                                    <i class="fas fa-link"></i>
                                    Requirement links: 
                                    <span class="badge badge-primary mr-2 pt-1">{{ $post->link_count }}</span>
                                </h6>
                            @endif
                        </div>

                        <div class="card-footer d-flex justify-content-end">
                            <a href="{{ route('post.show', [$post->id]) }}" class=" text-dark">
                                Comment
                                <span class="badge badge-primary pt-1">{{ $post->comment_count }}</span>
                            </a>
                        </div>
                    </a>
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
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        </div>  
                    </div>
                </div>
            @endif
        </div>
    </div>


    <script>
        $(".requirement-item-hover").hover(function () {
            $(this).toggleClass("shadow-lg");
        });
    </script>
</div>
