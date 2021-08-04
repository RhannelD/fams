<div>
    <div class="form-group">
        <label for="answer_{{ $requirement_item->id }}">Answer</label>
        <textarea wire:model.lazy="answer.answer" class="form-control" id="answer_{{ $requirement_item->id }}" rows="2"></textarea>
        @error('answer.answer') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div>
