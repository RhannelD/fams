<div class="shadow p-2 mx-2 bg-white rounded item-hover mx-auto mb-3 p-3" style="max-width: 760px">
    <label class="mb-1">
        <strong>
            {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
        </strong>
    </label>
    <div class="form-group my-0">
        <div class="d-flex">
            <textarea wire:model.lazy="comment.comment" class="form-control rounded" rows="1" placeholder="Comment something..."></textarea>
            <div>
                <button wire:click="comment" class="btn btn-dark rounded ml-2" type="button">
                    <i class="fas fa-edit"></i>
                </button>
            </div>
        </div>
        @error('comment.comment') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div>
