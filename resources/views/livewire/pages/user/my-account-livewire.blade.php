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
                            Gender: 
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
                            Email: 
                        </td>
                        <td>
                            {{ $user->email }}
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
                </table>
            </div>
        </div>
    </div>
    <hr>
    @if ( $user->is_scholar() && $user->scholarship_scholars->count()>0 )
        @include('livewire.pages.user.my-account-scholarship-scholar-livewire')
        <hr>
    @endif
    @if ( $user->is_officer() && $user->scholarship_officers->count()>0 )
        @include('livewire.pages.user.my-account-scholarship-officer-livewire')
        <hr>
    @endif
    <div class="d-flex justify-content-end">
        <button class="btn btn-primary mx-1" data-toggle="modal" data-target="#update-facebook-modal"
            wire:click="$emitTo('user.change-facebook-livewire', 'reset_values')"
            >
            Update Facebook
        </button>
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
    @if ( Auth::user()->is_scholar() )
        @livewire('user.change-facebook-livewire')
    @endif

    <script>
		window.addEventListener('change-password-form', event => {
			$("#change-password-modal").modal(event.detail.action);
		});
		window.addEventListener('update-email-form', event => {
			$("#update-email-modal").modal(event.detail.action);
		});
		window.addEventListener('change-facebook-form', event => {
			$("#update-facebook-modal").modal(event.detail.action);
		});
    </script>
@endisset
</div>
