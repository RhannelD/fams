<div>
    <div class="form-group">
        <label for="answer_{{ $requirement_item_id }}">Answer</label>
        <textarea wire:model.lazy="answer.answer" class="form-control" id="answer_{{ $requirement_item_id }}" rows="2"
            @isset( $response->submit_at )
                disabled
            @endisset    
            >
        </textarea>
        @error('answer.answer') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div>
