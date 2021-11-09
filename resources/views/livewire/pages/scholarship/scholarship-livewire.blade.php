<div>
	<nav class="navbar navbar-expand-lg navbar-dark bg-mid-bar border-bottom-0 pb-1">
        <div class="input-group col-md-6 mb-1 px-0">
			<div class="input-group rounded">
				<input type="search" class="form-control rounded btn-white border-white" placeholder="Search Scholarships" wire:model.debounce.1000ms='search'/>
				<span wire:click="$emitSelf('refresh')" class="input-group-text bg-white border-0 ml-1">
					<i class="fas fa-search"></i>
				</span>
			</div>
		</div>

		<div class="col-md-6 mb-1 px-0">
			@can('create', [\App\Models\Scholarship::class])
				<div class="input-group rounded">
					<button class="btn btn-secondary ml-auto mr-0" type="button" wire:click="nullinputs" data-toggle="modal" data-target="#scholarship_form">
						<i class="fas fa-plus"></i>
						Create Scholarship
					</button>
				</div>
			@endcan
		</div>
    </nav>

	<div class="row mx-1 mt-2">
		@forelse ($scholarships as $scholarship)
			<div class="col-12 col-sm-6 col-lg-4 d-flex flex-column">
				<div class="card mb-2 flex-grow-1 border-{{ session()->has("border-success-{$scholarship->id}")? session("border-success-{$scholarship->id}"): 'secondary' }}">
					<div class="card-header bg-{{ session()->has("border-success-{$scholarship->id}")? session("border-success-{$scholarship->id}"): 'secondary' }}">
						<div class="d-flex">
							<h4 class="my-1">
								<a class="text-decoration-none"
									@if ( $scholarship->categories->count() )
										href="{{ route('scholarship.home', [$scholarship->id]) }}"
									@else
										href="{{ route('scholarship.category', [$scholarship->id]) }}"
									@endif
									>
									<strong class="text-white">{{ $scholarship->scholarship }}</strong>
								</a>
							</h4>
							
							@can('delete', $scholarship)
								<div class="ml-auto mr-0">
									<div class="btn-group">
										<button type="button" class="btn btn-sm btn-secondary btn-outline-light rounded" data-toggle="dropdown" aria-expanded="false">
											<i class="fas fa-ellipsis-v"></i>
										</button>
										<div class="dropdown-menu dropdown-menu-right">
											<button class="dropdown-item" type="button" wire:click="edit({{ $scholarship->id }})" data-toggle="modal" data-target="#scholarship_form">
												<i class="fas fa-edit"></i>
												Edit Info
											</button>
											<button class="dropdown-item" type="button" wire:click="confirm_delete({{ $scholarship->id }})">
												<i class="fas fa-trash"></i>
												Delete
											</button>
										</div>
									</div>
								</div>
							@endcan
						</div>
					</div>
					<div class="card-body">
						<table>
							<tr>
								<td>Scholars:</td>
								<td>{{ $scholarship->get_num_of_scholars() }}</td>
							</tr>
							@if ( $scholarship->get_num_of_pending_application_responses() )
								<tr>
									<td>Applications:</td>
									<td>{{ $scholarship->get_num_of_pending_application_responses() }}</td>
								</tr>
							@endif
							@if ( $scholarship->get_num_of_pending_renewal_responses() )
								<tr>
									<td>Renewals:</td>
									<td>{{ $scholarship->get_num_of_pending_renewal_responses() }}</td>
								</tr>
							@endif
						</table>
					</div>
				</div>
			</div>
		@empty
			<div class="col-12">
				<div class="alert alert-info">
						No results.
				</div>
			</div>
		@endforelse

	</div>
		
	@can('create', [\App\Models\Scholarship::class])
		<div>
			<div wire:ignore.self class="modal fade scholarship_form" id="scholarship_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<form class="modal-content" wire:submit.prevent="save()">
						<div class="modal-header bg-dark text-white">
						<h5 class="modal-title" id="exampleModalCenterTitle">Scholarship {{ ((isset($scholarship_id))? 'Editing': 'Creating') }}</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true"><i class="fas fa-times-circle text-white"></i></span>
							</button>
						</div>
						<div class="modal-body student_creating">
							<div wire:loading wire:target='edit' class="w-100 alert alert-info">
								Loading...
							</div>
							<div wire:loading.remove wire:target='edit'>
								@include('livewire.form.scholarship-form-livewire')
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-success">
								<i class="fas fa-save" wire:loading.remove wire:target="save"></i>
								<i class="fas fa-spinner fa-spin" wire:loading wire:target="save"></i>
								Save
							</button>
							<button type="button" data-dismiss="modal" class="btn btn-secondary" id="cancel_edit">
								<i class="fas fa-times"></i>
								Cancel
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	@endcan

	<script>
		window.addEventListener('scholarship-info', event => {
			$(".info_scholarship").collapse(event.detail.action);
		});

		window.addEventListener('scholarship-form', event => {
			$(".scholarship_form").modal(event.detail.action);
		});
		
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
</div>
