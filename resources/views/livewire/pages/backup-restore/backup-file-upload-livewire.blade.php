<div>
    <div wire:ignore.self class="modal fade" id="upload_backup_modal" tabindex="-1" aria-labelledby="upload_backup_modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="upload_backup_modalLabel">Upload Backup File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupFileAddon01">
                                <div wire:target="file" wire:loading.remove>
                                    <i class="fas fa-file-upload"></i>
                                </div>
                                <div wire:target="file" wire:loading>
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                            </span>
                        </div>
                        <div class="custom-file">
                            <input wire:model="file" type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                            <label class="custom-file-label" for="inputGroupFile01">
                                @if ( isset($filename) )
                                    {{ $filename }}
                                @else
                                    Backup File Upload
                                @endif
                            </label>
                        </div>
                    </div>
                    @error('file') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button wire:click="upload" wire:loading.attr="disabled" wire:target="upload" type="button" class="btn btn-success"
                        @if ( !isset($file) )
                            disabled
                        @endif
                        >
                        <i wire:loading.remove wire:target="upload" class="fas fa-cloud-upload-alt"></i>
                        <i wire:loading wire:target="upload" class="fas fa-circle-notch fa-spin"></i>
                        Upload
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('upload-form', event => {
			$("#upload_backup_modal").modal(event.detail.action);
		});
    </script>
</div>
