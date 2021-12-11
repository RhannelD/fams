@isset($pending_invites[0])
    <div class="d-flex justify-content-end">
        <button class="btn btn-danger text-white"
            wire:click='cancel_all_invite_confirm'
            wire:loading.attr='disabled'
            wire:target='cancel_all_invites'
            >
            <i class="fas fa-spinner fa-spin"
                wire:loading
                wire:target='cancel_all_invites'
                >
            </i>
            Cancel all
        </button>
    </div>
@endisset
@forelse ($pending_invites as $invite)
    <div class="input-group my-1">
        <input type="text" class="form-control bg-white border-info" 
            value="{{ $invite->email }}" readonly>
        <div class="input-group-append">
            @if ( count($categories)>1 )
                <span class="input-group-text border-info bg-white">
                    {{ $invite->category->category }}
                </span>
            @endif
            <span class="input-group-text border-info bg-white">
                {{ $invite->acad_year }}-{{ $invite->acad_year+1 }} {{ ($invite->acad_semt==1)? '1st': '2nd' }} Sem
            </span>
            @if ( !isset($invite->sent) )
                <span class="input-group-text border-info bg-white rounded-right text-info">
                    <i class="fas fa-circle-notch fa-spin"></i>
                </span>
            @elseif ( $invite->sent )
                <span class="input-group-text border-info bg-white rounded-right text-success">
                    <i class="fas fa-check-circle"></i>
                </span>
            @else
                <span class="input-group-text border-info bg-white text-danger">
                    <i class="fas fa-exclamation-circle"></i>
                </span>
                <button class="btn btn-success rounded-right" type="button"
                    wire:click='resend_rejected_invite({{ $invite->id }})' 
                    wire:loading.attr="disabled"
                    >
                    <i class="fas fa-sync-alt" id="resend_rejected_invite_load_{{ $invite->id }}"
                        wire:loading.class="fa-spin"
                        wire:target="resend_rejected_invite('{{ $invite->id }}')">
                </i>
            </button>
            @endif
            <button class="btn btn-danger ml-1 rounded" type="button"
                wire:click="cancel_invite({{ $invite->id }})" 
                wire:loading.attr="disabled"
                >
                <i class="fas fa-times-circle" id="cancel_invite_icon_{{ $invite->id }}"
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
    <input type="text" class="form-control bg-white border-info mt-1" value="None" readonly>
@endforelse
