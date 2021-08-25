<form class="modal-content" wire:submit.prevent="save()">
    <div class="modal-header bg-dark text-white">
    <h5 class="modal-title" id="exampleModalCenterTitle">Officer Invite</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fas fa-times-circle text-white"></i></span>
        </button>
    </div>
    <div class="modal-body student_creating">
        <div class="row"> 
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name_email">Enter name or email</label>
                    <input wire:model.lazy="name_email" type="text" class="form-control" placeholder="Enter name or email" id="name_email">
                </div>
        
                @if(!$errors->has('name_email') && !empty($name_email) )
                    <h6 class="mb-1">Invite via email</h6>
                    <hr class="my-1">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control bg-white border-success" value="{{ $name_email }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-success" type="button">
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
                                <button class="btn btn-success" type="button">
                                    Invite
                                </button>
                            </div>
                        </div>
                    @empty
                        <input type="text" class="form-control bg-white" value="No results..." readonly>
                    @endforelse
                @endisset
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save" wire:loading.remove wire:target="save"></i>
            <i class="fas fa-spinner fa-spin" wire:loading wire:target="save"></i>
            Save
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-secondary" id="cancel_edit">
            <i class="fas fa-times"></i>
            Cancel
        </button>
    </div>
</form>

