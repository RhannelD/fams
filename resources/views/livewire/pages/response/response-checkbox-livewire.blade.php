<div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="checkbox_{{ $this->checkbox->id }}"
            wire:click="save()"
            @if ($option_checked)
                checked
            @endif

            @isset( $is_submitted )
                disabled
            @endisset
            >
        <label class="custom-control-label" for="checkbox_{{ $this->checkbox->id }}">{{ $this->checkbox->option }}</label>
    </div>
</div>
