<div>
    @isset($scholarship_program)
        
        <div class="card mt-2 mb-1">
            <h4 class="card-header bg-dark text-white">Scholarship Program Info</h4>
            <div class="card-body">
                <table>
                    <tbody>
                        <tr>
                            <td>ID:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $scholarship_program->id }}</td>
                        </tr>
                        <tr>
                            <td>Scholarship:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $scholarship_program->scholarship }}</td>
                        </tr>
                        <tr>
                            <td>Scholars:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $scholarship_program->scholars_count() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('scholarship.home', [$scholarship_program->id]) }}"
                    class="btn btn-info text-white mb-1 mb-lg-0">
                    Open Scholarship
                </a>
                @can('delete', $scholarship_program)
                    <button type="button" class="btn btn-info mb-1 mb-lg-0 text-white" wire:click="edit()" data-toggle="modal" data-target="#scholarship_form">
                        <i class="fas fa-edit"></i>
                        Edit Info
                    </button>
                    <button class="btn btn-danger text-white mb-1 mb-lg-0" wire:click="confirm_delete()">
                        <i class="fas fa-trash"></i>
                        Delete
                    </button>
                @endcan
            </div>
        </div>

        <script>

        window.addEventListener('swal:confirm:delete_scholarship', event => { 
            swal({
              title: event.detail.message,
              text: event.detail.text,
              icon: event.detail.type,
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                @this.call(event.detail.function)
              }
            });
        });

        </script>

    @endisset
</div>