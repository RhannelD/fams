<div>
    @isset($user)
        
        <div class="card mt-2 mb-1">
            <h4 class="card-header bg-dark text-white">Scholar Info</h4>
            <div class="card-body">
                <table>
                    <tbody>
                        <tr>
                            <td>ID:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user->id }}</td>
                        </tr>
                        <tr>
                            <td>Full Name:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user->firstname }} {{ $user->middlename }} {{ $user->lastname }}</td>
                        </tr>
                        <tr>
                            <td>Phonenumber:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user->phone }}</td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td>Sex:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user->gender }}</td>
                        </tr>
                        <tr>
                            <td>Address:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user->address() }}</td>
                        </tr>
                        <tr>
                            <td>Religion:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user->religion }}</td>
                        </tr>
                        <tr>
                            <td>Birth Date:</td>
                            <td class="pl-sm-1 pl-md-2">{{ \Carbon\Carbon::parse($user->birthday)->format("M d,  Y h:i A") }}</td>
                        </tr>
                        <tr>
                            <td>Birth Place:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user->birthplace }}</td>
                        </tr>
                    </tbody>
                </table>
                <hr class="my-2">
                <table>
                    <tbody>
                        <tr>
                            <td>SR-Code:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->srcode }}</td>
                        </tr>
                        <tr>
                            <td>Course:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->course->course }}</td>
                        </tr>
                        <tr>
                            <td>Year:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->year }}</td>
                        </tr>
                        <tr>
                            <td>Semester:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->semester }}</td>
                        </tr>
                    </tbody>
                </table>
                <hr class="my-2">
                <table>
                    <tbody>
                        <tr>
                            <td>Mother's Name:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->mother_name }}</td>
                        </tr>
                        <tr>
                            <td>Mother's Occupation:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->mother_occupation }}</td>
                        </tr>
                        <tr>
                            <td>Mother's Birth Date:</td>
                            <td class="pl-sm-1 pl-md-2">{{ \Carbon\Carbon::parse($user->scholar_info->mother_birthday)->format("M d,  Y h:i A") }}</td>
                        </tr>
                        <tr>
                            <td>Mother's Educational Attainment:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->mother_educational_attainment }}</td>
                        </tr>
                        <tr>
                            <td>Living:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->mother_living? 'Yes': 'No' }}</td>
                        </tr>
                        <tr>
                            <td>Father's Name:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->father_name }}</td>
                        </tr>
                        <tr>
                            <td>Father's Occupation:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->father_occupation }}</td>
                        </tr>
                        <tr>
                            <td>Father's Birth Date:</td>
                            <td class="pl-sm-1 pl-md-2">{{ \Carbon\Carbon::parse($user->scholar_info->father_birthday)->format("M d, Y h:i A") }}</td>
                        </tr>
                        <tr>
                            <td>Father's Educational Attainment:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->father_educational_attainment }}</td>
                        </tr>
                        <tr>
                            <td>Living:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->father_living? 'Yes': 'No' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <button wire:click="$emitTo('scholar.scholar-edit-livewire', 'edit', {{ $user->id }})" type="button" 
                    class="btn btn-info mb-1 mb-lg-0 text-white" data-toggle="modal" data-target="#scholar_form">
                    <i class="fas fa-edit"></i>
                    Edit Info
                </button>
                
                <button class="btn btn-info ml-auto mr-0 mb-1 mb-lg-0 text-white" type="button" data-toggle="modal" data-target="#change_password_form">
                    <i class="fas fa-lock"></i>
                    Change Password
                </button>

                <button class="btn btn-danger text-white mb-1 mb-lg-0" wire:click="confirm_delete">
                    <i class="fas fa-trash"></i>
                    Delete
                </button>
            </div>
        </div>

        @livewire('scholar.scholar-scholarship-livewire', [$user->id], key('scholar-scholarships-'.time().$user->id))

		<div>
			<div wire:ignore.self class="modal fade" id="change_password_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-md" role="document">
					<form class="modal-content" wire:submit.prevent="change_pass()">
						<div class="modal-header bg-dark text-white">
						<h5 class="modal-title" id="exampleModalCenterTitle">Change Password</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true"><i class="fas fa-times-circle text-white"></i></span>
							</button>
						</div>
						<div class="modal-body student_creating">
							@include('livewire.form.user-change-password-form-livewire')
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-success">
								<i class="fas fa-save" wire:loading.remove wire:target="change_pass"></i>
								<i class="fas fa-spinner fa-spin" wire:loading wire:target="change_pass"></i>
								Update
							</button>
							<button type="button" data-dismiss="modal" class="btn btn-secondary">
								<i class="fas fa-times"></i>
								Cancel
							</button>
						</div>
					</form>
				</div>
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