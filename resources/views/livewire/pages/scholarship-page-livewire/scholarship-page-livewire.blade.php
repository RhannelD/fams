<div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card mb-4 shadow requirement-item-hover mx-auto" style="max-width: 800px">
                <div class="card-header">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Post something...">
                        <div class="input-group-append">
                            <button class="btn btn-dark" type="button">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            @foreach ($posts as $post)
                <div class="card mb-3 shadow requirement-item-hover mx-auto" style="max-width: 800px">
                    <div class="card-header d-flex"> 
                        <div class="mr-auto bd-highlight">
                            <h5 class="my-0">
                                {{ $post->title }} 
                            </h5>
                        </div>
                        <div class="ml-auto bd-highlight">
                            {{ $post->created_at }}
                        </div>
                    </div>
                    <div class="card-header">
                        <p>
                            {{ $post->post }}
                        </p>
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
