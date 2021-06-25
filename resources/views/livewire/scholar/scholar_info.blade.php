<div>
    @isset($scholar)
        
        <div class="card my-5 my">
            <h4 class="card-header bg-dark text-white">Scholar Info</h4>
            <div class="card-body">
                <table>
                    <tbody>
                        <tr>
                            <td>ID:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $scholar->id }}</td>
                        </tr>
                        <tr>
                            <td>Firstname:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $scholar->firstname }}</td>
                        </tr>
                        <tr>
                            <td>Middlename:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $scholar->middlename }}</td>
                        </tr>
                        <tr>
                            <td>Lastname:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $scholar->lastname }}</td>
                        </tr>
                        <tr>
                            <td>Phonenumber:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $scholar->phone }}</td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $scholar->email }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-info mt-1" wire:click="edit({{ $scholar->id }})" data-toggle="modal" data-target="#scholar_form">
                    <i class="fas fa-edit"></i>
                    Edit Info
                </button>
                <button class="btn btn-danger text-white mt-1" wire:click="confirm_delete({{ $scholar->id }})">
                    <i class="fas fa-trash"></i>
                    Delete
                </button>
            </div>
        </div>

        <script>

        window.addEventListener('swal:confirm:delete_scholar', event => { 
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