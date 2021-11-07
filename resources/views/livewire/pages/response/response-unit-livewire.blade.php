<div>
    <div class="form-group my-auto">
        <label for="gwa_{{ $requirement_item_id }}">Units</label>
        <input type="number" wire:model.lazy="unit.units" class="form-control" id="units_{{ $requirement_item_id }}" placeholder="Units"
            @isset( $response->submit_at )
                disabled
            @endisset   
            min="{{ $units_limit['min'] }}" 
            max="{{ $units_limit['max'] }}" 
            >
        @error('unit.units') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div>
