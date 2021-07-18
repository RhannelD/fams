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

    <div wire:ignore.self class="modal fade" id="post_something" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="post_something_label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form class="modal-content" wire:submit.prevent="save">
                <div class="modal-header">
                    <h5 class="modal-title" id="post_something_label">Post</h5>
                    <button type="button" class="close close_post_modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input wire:model.lazy="post.title" type="text" class="form-control form-control-lg" placeholder="Title (Optional)">
                        @error('post.title') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="post_post">Post</label>
                        <textarea wire:model.lazy="post.post" class="form-control" id="post_post" rows="5" placeholder="Post something..."></textarea>
                        @error('post.post') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Post </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.addEventListener('close_post_modal', event => { 
            $('.close_post_modal').click();
        });
    </script>
</div>
