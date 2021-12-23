<div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-mid-bar border-bottom-0 pb-1">
        <div class="col-md-6 mb-1 px-0">
            <h4 class="font-weight-bold text-white my-auto">
                Backup and Restore
            </h4>
        </div>
		<div class="col-md-6 mb-1 px-0">
			<div class="input-group rounded d-flex flex-nowrap">
				<button wire:click="create_backup" class="btn btn-success ml-auto mr-0" type="button" data-toggle="modal" data-target="#scholar_form">
					<i wire:loading.remove wire:target="create_backup" class="fas fa-plus"></i>
                    <i wire:loading wire:target="create_backup" class="fas fa-spinner fa-spin"></i>
					Create New Backup
				</button>
                <button class="btn btn-primary ml-1" type="button" data-toggle="modal" data-target="#upload_backup_modal">
                    <i class="fas fa-cloud-upload-alt"></i>
                    Upload Backup
                </button>
			</div>
		</div>
	</nav>

    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-12 table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-right">
                                #
                            </th>
                            <th>
                                Back-Up Files
                            </th>
                            <th class="text-center">
                                Date
                            </th>
                            <th class="text-center">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($files as $file)   
                            @if ( $loop->index >= $show_row )
                                <tr id="row_load_more">
                                    <td colspan="10" id="row_load_cell">
                                        <div class="w-100 d-flex justify-content-center">
                                            <button wire:click='load_more' class="btn btn-primary mb-3 mt-1" id="load_more_btn">
                                                Load more
                                                <i wire:loading.remove wire:target='load_more' class="fas fa-chevron-circle-down"></i>
                                                <i wire:loading wire:target='load_more' class="fas fa-circle-notch fa-spin"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @break
                            @endif
                            <tr id="row_{{ $file['filename'] }}"
                                @if ( $file['filename']==$file_created )
                                    class="table-success"
                                @endif
                                >
                                <th class="text-right">
                                    {{ $loop->index+1 }}
                                </th>
                                <td>
                                    {{ $file['filename'] }}
                                </td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($file['lastModified'])->format('M d, Y h:i A') }}
                                </td>
                                <td class="text-center py-2 text-nowrap">
                                    <button wire:click="download('{{ $file['filename'] }}')" wire:loading.attr="disabled" class="btn btn-sm btn-primary" id="download_btn_{{ $file['filename'] }}">
                                        <i wire:loading.remove wire:target="download('{{ $file['filename'] }}')" class="fas fa-file-download"></i>
                                        <i wire:loading wire:target="download('{{ $file['filename'] }}')" class="fas fa-circle-notch fa-spin"></i>
                                        Download
                                    </button>
                                    <button wire:click="restore_confirm('{{ $file['filename'] }}')" wire:loading.attr="disabled" wire:target="restore_confirm, restore" class="btn btn-sm btn-success" id="restore_btn_{{ $file['filename'] }}">
                                        <i wire:loading.remove wire:target="restore('{{ $file['filename'] }}')" class="fas fa-database"></i>
                                        <i wire:loading wire:target="restore('{{ $file['filename'] }}')" class="fas fa-circle-notch fa-spin"></i>
                                        Restore
                                    </button>
                                    <button wire:click="delete_confirm('{{ $file['filename'] }}')" wire:loading.attr="disabled" wire:target="delete_confirm, delete" class="btn btn-sm btn-danger" id="delete_btn_{{ $file['filename'] }}">
                                        <i wire:loading.remove wire:target="delete('{{ $file['filename'] }}')" class="fas fa-trash"></i>
                                        <i wire:loading wire:target="delete('{{ $file['filename'] }}')" class="fas fa-circle-notch fa-spin"></i>
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @livewire('backup-restore.backup-file-upload-livewire')

    <script>
        window.addEventListener('swal:confirm:delete_backup', event => { 
            swal({
				title: event.detail.message,
				text: event.detail.text,
				icon: event.detail.type,
				buttons: true,
				dangerMode: true,
            }).then((willDelete) => {
				if (willDelete) {
					@this.delete(event.detail.backup_filename)
				}
            });
        });

        window.addEventListener('swal:confirm:restore_database', event => { 
            swal({
				title: event.detail.message,
				text: event.detail.text,
				icon: event.detail.type,
				buttons: true,
				dangerMode: true,
            }).then((willDelete) => {
				if (willDelete) {
					@this.restore(event.detail.backup_filename)
				}
            });
        });
    </script>
</div>
