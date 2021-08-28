<form class="modal-content" wire:submit.prevent="save()">
    <div class="modal-header bg-dark text-white">
    <h5 class="modal-title" id="exampleModalCenterTitle">Scholar Invite</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fas fa-times-circle text-white"></i></span>
        </button>
    </div>
    <div class="modal-body student_creating">
        <div class="row"> 
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name_email">Enter name or email</label>
                    <input wire:model.debounce.1000ms="name_email" type="text" class="form-control" placeholder="Enter name or email" id="name_email">
                </div>
        
                @if(!$errors->has('name_email') && !empty($name_email) )
                    <h6 class="mb-1">Invite via email</h6>
                    <hr class="my-1">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control bg-white border-success" value="{{ $name_email }}" readonly>
                        <div class="input-group-append">
                            <button wire:click="invite_email('{{ $name_email }}')" class="btn btn-success" type="button">
                                Invite
                            </button>
                        </div>
                    </div>
                @endif
        
                @isset($search_officers) 
                    <h6 class="mb-1">Officer results</h6>
                    <hr class="my-1">
                    @forelse ($search_officers as $officer)
                        <div class="input-group mb-1">
                            <input type="text" class="form-control bg-white border-success" value="{{ $officer->flname() }} / {{ $officer->email }}" readonly>
                            <div class="input-group-append">
                                <button wire:click="invite_email('{{ $officer->email }}')" class="btn btn-success" type="button">
                                    Invite
                                </button>
                            </div>
                        </div>
                    @empty
                        <input type="text" class="form-control bg-white" value="No results..." readonly>
                    @endforelse
                @endisset
            </div>

            <div class="col-md-6">
                <h6 class="mt-1">Pending invites</h6>
                @forelse ($invites as $invite)
                    <div class="input-group mb-1">
                        <input type="text" class="form-control bg-white border-info" value="{{ $invite->email }}" readonly>
                        <div class="input-group-append">
                            <button wire:click="cancel_invite({{ $invite->id }})" wire:loading.attr="disabled" class="btn btn-danger" type="button">
                                <span wire:loading.remove wire:target="cancel_invite({{ $invite->id }})">Cancel</span>
                                <i wire:loading wire:target="cancel_invite({{ $invite->id }})" class="fas fa-spinner fa-spin"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <input type="text" class="form-control bg-white border-info" value="None" readonly>
                @endforelse
            </div>
        </div>
    </div>
</form>
