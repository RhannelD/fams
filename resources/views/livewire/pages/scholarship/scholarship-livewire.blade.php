<div>
	<div class="row mb-2 mx-1">
		<div class="input-group col-md-6 mt-2">

			<div class="input-group rounded">
				<input type="search" class="form-control rounded" placeholder="Search Scholarships" wire:model.debounce.1000ms='search'/>
				<span wire:click="$emitSelf('refresh')" class="input-group-text border-0">
					<i class="fas fa-search"></i>
				</span>
			</div>

		</div>

		<div class="col-md-6 mt-2">

			@can('create', [\App\Models\Scholarship::class])
				<div class="input-group rounded">
					<button class="btn btn-info ml-auto mr-0 text-white" type="button" wire:click="nullinputs" data-toggle="modal" data-target="#scholarship_form">
						<i class="fas fa-plus"></i>
						Create Scholarship
					</button>
				</div>
			@endcan

		</div>
	</div>

	<hr class="my-2 mx-2">
	<div class="row mx-1">
		@forelse ($scholarships as $scholarship)
			<div class="col-12 col-sm-6 col-lg-4">
				<div class="card mb-2 border-dark">
					<div class="card-header bg-dark text-white">
						<h4 class="my-1">
							<a href="{{ route('scholarship.home', [$scholarship->id]) }}">
								<strong class="text-white">{{ $scholarship->scholarship }}</strong>
							</a>
						</h4>
					</div>
					<div class="card-body">
						<table>
							<tr>
								<td>Scholars:</td>
								<td>{{ $scholarship->get_num_of_scholars() }}</td>
							</tr>
							<tr>
								<td>Applications:</td>
								<td>{{ $scholarship->get_num_of_pending_application_responses() }}</td>
							</tr>
							<tr>
								<td>Renewals:</td>
								<td>{{ $scholarship->get_num_of_pending_renewal_responses() }}</td>
							</tr>
						</table>
					</div>
					@can('delete', $scholarship)
						<div class="card-footer">
							<button type="button" class="btn btn-info mb-1 mb-lg-0 text-white" wire:click="edit({{ $scholarship->id }})" data-toggle="modal" data-target="#scholarship_form">
								<i class="fas fa-edit"></i>
								Edit Info
							</button>
							<button class="btn btn-danger text-white mb-1 mb-lg-0" wire:click="confirm_delete({{ $scholarship->id }})">
								<i class="fas fa-trash"></i>
								Delete
							</button>
						</div>
					@endcan
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
