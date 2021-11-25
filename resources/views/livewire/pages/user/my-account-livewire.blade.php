<div class="m-2">
@isset($user)
    <h2 class="my-2">
        <strong>Your Account</strong>
    </h2>
    <hr>
    <div class="container-fluid px-md-3 px-0">
        <div class="d-flex">
            <h4 class="my-auto ml-0 mr-auto">
                <strong>
                    Personal Information
                </strong>
            </h4>
            <div class="mr-0">
                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#update-personal-info"
                    wire:click="$emitTo('user.update-personal-information', 'reset_values')"
                    >
                    <i class="fas fa-user-edit"></i>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-auto">
                <table>
                    <tr>
                        <td>
                            Name: 
                        </td>
                        <td>
                            {{ $user->fmlname() }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Email: 
                        </td>
                        <td>
                            {{ $user->email }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Sex: 
                        </td>
                        <td>
                            {{ Str::ucfirst($user->gender) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Religion: 
                        </td>
                        <td>
                            {{ $user->religion }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Age: 
                        </td>
                        <td>
                            {{ $user->age() }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-auto">
                <table>
                    <tr>
                        <td>
                            Phone Number: 
                        </td>
                        <td>
                            {{ $user->phone }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Birth Date: 
                        </td>
                        <td>
                            {{ date_format(new DateTime($user->birthday),"M d, Y") }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Birth Place: 
                        </td>
                        <td>
                            {{ $user->birthplace }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Address: 
                        </td>
                        <td>
                            {{ $user->address }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <hr class="my-2">
    </div>
    @if ( $user->is_scholar() || $user->scholarship_scholars->count()>0 )
        @include('livewire.pages.user.my-account-scholarship-scholar-livewire')
        <hr>
    @endif
    <div class="d-flex justify-content-end">
        <button class="btn btn-primary mx-1" data-toggle="modal" data-target="#update-email-modal"
            wire:click="$emitTo('user.change-email-livewire', 'reset_values')"
            >
            Update Email
        </button>
        
        @if ( Auth::user()->is_scholar() )
            <button class="btn btn-primary" data-toggle="modal" data-target="#change-password-modal"
                wire:click="$emitTo('user.change-password-livewire', 'reset_values')"
                >
                Change Password
            </button>
        @endif
    </div>

    @livewire('user.change-password-livewire')
    @livewire('user.change-email-livewire')
    @livewire('user.update-personal-information')
    @if ( Auth::user()->is_scholar() )
        @livewire('user.change-facebook-livewire')
        @livewire('user.update-education-information-livewire')
        @livewire('user.update-family-information-livewire')
    @endif

    <script>
		window.addEventListener('change-personal-info-form', event => {
			$("#update-personal-info").modal(event.detail.action);
		});
		window.addEventListener('change-password-form', event => {
			$("#change-password-modal").modal(event.detail.action);
		});
		window.addEventListener('update-email-form', event => {
			$("#update-email-modal").modal(event.detail.action);
		});
		window.addEventListener('change-facebook-form', event => {
			$("#update-facebook-modal").modal(event.detail.action);
		});
		window.addEventListener('update-family-info-form', event => {
			$("#update-family-info-modal").modal(event.detail.action);
		});
		window.addEventListener('update-education-info-form', event => {
			$("#update-education-info-modal").modal(event.detail.action);
		});
    </script>
@endisset
</div>
