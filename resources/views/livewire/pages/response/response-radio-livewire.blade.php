<div>
    @foreach ($options as $option_item)
        <div class="form-check">
            <input wire:model="option.option_id" class="form-check-input" type="radio" 
                name="option_{{ $requirement_item->id }}" id="option_{{ $option_item->id }}" value="{{ $option_item->id }}"
                @isset( $option->response->submit_at )
                    disabled
                @endisset
                >
            @if ( isset($option->response->submit_at) && $option->option_id == $option_item->id)
                <label  for="option_{{ $option_item->id }}" class="form-check-label text-dark">
                    <strong>{{ $option_item->option }}</strong>
                </label>
            @else
                <label  for="option_{{ $option_item->id }}" class="form-check-label">
                    {{ $option_item->option }}
                </label>
            @endif
        </div>
    @endforeach
    @error('option.option_id') <span class="text-danger">{{ $message }}</span> @enderror

    @if( isset($option->id) && !isset( $option->response->submit_at ) )
        <div wire:click="clear_selection" class="d-flex">
            <button class="btn btn-danger btn-sm mt-1 mr-1 ml-auto">
                <i wire:loading.remove wire:target="clear_selection" class="fas fa-times-circle mr-1"></i>
                <i wire:loading wire:target="clear_selection" class="fas fa-spinner fa-spin mr-1"></i>
                Clear Selection
            </button>
        </div>
    @endif
</div>
