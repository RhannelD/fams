<div>
    @isset($scholarship_program)
        
        <div class="card mt-2">
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
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-info mt-1 text-white" wire:click="edit({{ $scholarship_program->id }})" data-toggle="modal" data-target="#scholarship_form">
                    <i class="fas fa-edit"></i>
                    Edit Info
                </button>
                <button class="btn btn-danger text-white mt-1" wire:click="confirm_delete({{ $scholarship_program->id }})">
                    <i class="fas fa-trash"></i>
                    Delete
                </button>
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