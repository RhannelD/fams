<div>
    <div class="form-group my-auto">
        <label for="gwa_{{ $requirement_item_id }}">GWA</label>
        <input type="number" wire:model.lazy="gwa.gwa" step='0.01' class="form-control" id="gwa_{{ $requirement_item_id }}" placeholder="Units"
            @isset( $response->submit_at )
                disabled
            @endisset   
            min="{{ $gwa_limit['min'] }}" 
            max="{{ $gwa_limit['max'] }}" 
            onchange="setTwoNumberDecimal()"
            >
        @error('gwa.gwa') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <script>
        function setTwoNumberDecimal(event) {
            $('#gwa_{{ $requirement_item_id }}').val(parseFloat($('#gwa_{{ $requirement_item_id }}').val()).toFixed(2));
        }
        $(document).ready(function() {
            $('#gwa_{{ $requirement_item_id }}').val(parseFloat($('#gwa_{{ $requirement_item_id }}').val()).toFixed(2));
        });
    </script>
</div>
