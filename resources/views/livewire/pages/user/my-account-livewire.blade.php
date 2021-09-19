<div>
@isset($user)
    <h2 class="my-2">
        <strong>Your Account</strong>
    </h2>
    <hr>
    <div class="container-fluid">
        <h5>
            <strong>
                Personal Information
            </strong>
        </h5>
        <div class="row">
            <div class="col-auto col-md-6">
                <table>
                    <tr>
                        <td>
                            Name: {{ $user->fmlname() }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Gender: {{ Str::ucfirst($user->gender) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Religion: {{ $user->religion }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Age: {{ $user->age() }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-auto col-md-6">
                <table>
                    <tr>
                        <td>
                            Phone Number: {{ $user->phone }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Email: {{ $user->email }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Birth Date: {{ date_format(new DateTime($user->birthday),"M d, Y") }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Birth Place: {{ $user->birthplace }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <hr>
    <div class="d-flex justify-content-end">
        <button class="btn btn-primary" data-toggle="modal" data-target="#change-password-modal">
            Change Password
        </button>
    </div>
    @livewire('user.change-password-livewire')

    <script>
		window.addEventListener('change-password-form', event => {
			$("#change-password-modal").modal(event.detail.action);
		});
    </script>
@endisset
</div>
