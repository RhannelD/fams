<div>
    @foreach ($options as $option_item)
        <div class="form-check">
            @if( isset($response->submit_at) || Auth::user()->cannot('submit', $response) )
                @if ($option_item->responses->count() > 0)
                    <i class="fas fa-check-circle"></i>
                @else
                    <i class="far fa-circle"></i>
                @endif
            @else
                <input class="form-check-input" type="radio" name="option_{{ $requirement_item_id }}" id="option_{{ $option_item->id }}" value="{{ $option_item->id }}"
                    wire:model="option_id">
            @endif
            @if ( isset($response->submit_at) && $option_item->responses->count() > 0 )
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
    @error('option_id') <span class="text-danger">{{ $message }}</span> @enderror

    @if( isset($option_id) && !isset( $response->submit_at ) && Auth::user()->can('submit', $response) )
        <div wire:click="clear_selection" class="d-flex">
            <button class="btn btn-danger btn-sm mt-1 mr-1 ml-auto">
                <i wire:loading.remove wire:target="clear_selection" class="fas fa-times-circle mr-1"></i>
                <i wire:loading wire:target="clear_selection" class="fas fa-spinner fa-spin mr-1"></i>
                Clear Selection
            </button>
        </div>
    @endif
</div>
