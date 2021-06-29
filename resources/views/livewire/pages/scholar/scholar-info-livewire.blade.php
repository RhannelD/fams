<div>
    @isset($user)
        
        <div class="card">
            <h4 class="card-header bg-dark text-white">Scholar Info</h4>
            <div class="card-body">
                <table>
                    <tbody>
                        <tr>
                            <td>ID:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user['id'] }}</td>
                        </tr>
                        <tr>
                            <td>Full Name:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user['firstname'] }} {{ $user['middlename'] }} {{ $user['lastname'] }}</td>
                        </tr>
                        <tr>
                            <td>Phonenumber:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user['phone'] }}</td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user['email'] }}</td>
                        </tr>
                        <tr>
                            <td>Gender:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user['gender'] }}</td>
                        </tr>
                        <tr>
                            <td>Religion:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user['religion'] }}</td>
                        </tr>
                        <tr>
                            <td>Birth Date:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user['birthday'] }}</td>
                        </tr>
                        <tr>
                            <td>Birth Place:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user['birthplace'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-info text-white" wire:click="edit({{ $user['id'] }})" data-toggle="modal" data-target="#scholar_form">
                    <i class="fas fa-edit"></i>
                    Edit Info
                </button>
                
                <button class="btn btn-info ml-auto mr-0 text-white" type="button" wire:click="nullinputs" data-toggle="modal" data-target="#change_password_form">
                    <i class="fas fa-lock"></i>
                    Change Password
                </button>

                <button class="btn btn-danger text-white" wire:click="confirm_delete({{ $user['id'] }})">
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