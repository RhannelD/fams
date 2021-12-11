@isset($accepted_invites[0])
    <div class="d-flex justify-content-end">
        <button class="btn btn-info text-white"
            wire:click='clear_all_accepted_invite_confirm'
            wire:loading.attr='disabled'
            wire:target='clear_all_accepted_invite'
            >
            <i class="fas fa-spinner fa-spin"
                wire:loading
                wire:target='clear_all_accepted_invite'
                >
            </i>
            Clear all
        </button>
    </div>
@endisset
@forelse ($accepted_invites as $invite)
    <div class="input-group my-1">
        <input type="text" class="form-control bg-white border-success {{ count($categories)<2? 'rounded': '' }}" 
            value="{{ $invite->email }}" readonly>
        <div class="input-group-append">
            @if ( count($categories)>1 )
                <span class="input-group-text border-success bg-white rounded-right">
                    {{ $invite->category->category }}
                </span>
            @endif
            <span class="input-group-text border-info bg-white">
                {{ $invite->acad_year }}-{{ $invite->acad_year+1 }} {{ ($invite->acad_semt==1)? '1st': '2nd' }} Sem
            </span>
            <button class="btn btn-dark rounded ml-1" type="button"
                wire:click="cancel_invite({{ $invite->id }})" 
                wire:loading.attr="disabled" 
                >
                <i class="fas fa-minus-circle" id="cancel_invite_icon_{{ $invite->id }}"
                    wire:loading.remove
                    wire:target="cancel_invite('{{ $invite->id }}')"
                    >
                </i>
                <i class="fas fa-spinner fa-spin" id="cancel_invite_load_{{ $invite->id }}"
                    wire:loading 
                    wire:target="cancel_invite('{{ $invite->id }}')">
                </i>
            </button>
        </div>
    </div>
@empty
    <input type="text" class="form-control bg-white border-info my-1" value="None" readonly>
@endforelse
