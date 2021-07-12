<div class="item_option_id_{{ $option->id }}">
    <div class="input-group mb-1">
        @if ($type == 'radio')
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input type="radio">
                </div>
            </div>
            <input wire:model='option.option' type="text" class="form-control bg-white rounded-right">
        @elseif ($type == 'check')
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input type="checkbox" name="">
                </div>
            </div>
            <input wire:model='option.option' type="text" class="form-control bg-white rounded-right">
        @endif
        <button wire:click="delete_option" class="btn btn-danger ml-1">
            <i class="fas fa-minus-circle"></i>
        </button>
    </div>
    @error('option.option') <span class="text-danger">{{ $message }}</span> @enderror
</div>
