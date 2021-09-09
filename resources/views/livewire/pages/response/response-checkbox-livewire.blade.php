<div>
@isset($option)
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="checkbox_{{ $option->id }}"
            @if ($option_checked)
                checked
            @endif

            @if( $is_submitted )
                disabled
            @else
                wire:click="save()"
            @endif
            >
        <label class="custom-control-label" for="checkbox_{{ $option->id }}">
            @if( $is_submitted )
                <span class="text-dark">
                    @if ($option_checked)
                        <strong>
                            {{ $option->option }}
                        </strong>
                    @else
                        {{ $option->option }}
                    @endif
                </span>
            @else
                {{ $option->option }}
            @endif
        </label>
    </div>
@endisset
</div>
