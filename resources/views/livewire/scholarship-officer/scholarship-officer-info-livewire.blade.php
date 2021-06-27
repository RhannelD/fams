<div>
    @isset($officer)
        
        <div class="card my-5 my">
            <h4 class="card-header bg-dark text-white">Scholar Info</h4>
            <div class="card-body">
                <table>
                    <tbody>
                        <tr>
                            <td>ID:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $officer->id }}</td>
                        </tr>
                        <tr>
                            <td>Full Name:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $officer->firstname }} {{ $officer->middlename }} {{ $officer->lastname }}</td>
                        </tr>
                        <tr>
                            <td>Phonenumber:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $officer->phone }}</td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $officer->email }}</td>
                        </tr>
                        <tr>
                            <td>Gender:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $officer->gender }}</td>
                        </tr>
                        <tr>
                            <td>Religiom:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $officer->religion }}</td>
                        </tr>
                        <tr>
                            <td>Birth Date:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $officer->birthday }}</td>
                        </tr>
                        <tr>
                            <td>Birth Place:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $officer->birthplace }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-info mt-1 text-white" wire:click="edit({{ $officer->id }})" data-toggle="modal" data-target="#officer_form">
                    <i class="fas fa-edit"></i>
                    Edit Info
                </button>
                <button class="btn btn-danger text-white mt-1" wire:click="confirm_delete({{ $officer->id }})">
                    <i class="fas fa-trash"></i>
                    Delete
                </button>
            </div>
        </div>

        <script>

        window.addEventListener('swal:confirm:delete_officer', event => { 
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