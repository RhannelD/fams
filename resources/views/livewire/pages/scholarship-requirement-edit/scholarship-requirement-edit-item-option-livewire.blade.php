<div class="item_option_id_{{ $option_id }}">
@isset($item_option)
    <div class="input-group mb-1">
        @if ($type == 'radio')
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <i class="far fa-circle"></i>
                </div>
            </div>
            <input wire:model.lazy='option.option' type="text" class="form-control bg-white rounded-right">
        @elseif ($type == 'check')
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <i class="far fa-square"></i>
                </div>
            </div>
            <input wire:model.lazy='option.option' type="text" class="form-control bg-white rounded-right">
        @endif
        <button wire:click="delete_option" class="btn btn-danger ml-1">
            <i class="fas fa-minus-circle"></i>
        </button>
    </div>
    @error('option.option') <span class="text-danger">{{ $message }}</span> @enderror 
@endisset
</div>
