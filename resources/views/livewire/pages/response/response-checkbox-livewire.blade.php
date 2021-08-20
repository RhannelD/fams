<div>
@isset($option)
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="checkbox_{{ $option->id }}"
            wire:click="save()"
            @if ($option_checked)
                checked
            @endif

            @isset( $is_submitted )
                disabled
            @endisset
            >
        <label class="custom-control-label" for="checkbox_{{ $option->id }}">{{ $option->option }}</label>
    </div>
@endisset
</div>
