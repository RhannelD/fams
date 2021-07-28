<form class="modal-content" wire:submit.prevent="save()">
    <div class="modal-header bg-dark text-white">
    <h5 class="modal-title" id="exampleModalCenterTitle">Scholar Account {{ ((isset($user->id))? 'Editing': 'Creating') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fas fa-times-circle text-white"></i></span>
        </button>
    </div>
    <div class="modal-body student_creating">
        @include('livewire.form.user-form-livewire')
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
