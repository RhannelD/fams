<div>
	<div class="row mb-1">
		<div class="input-group col-md-6 mt-2">

			<div class="input-group rounded">
				<input type="search" class="form-control rounded" placeholder="Search Scholarship Program" wire:model.debounce.1000ms='search'/>
				<span class="input-group-text border-0">
					<i class="fas fa-search"></i>
				</span>
			</div>

		</div>

		<div class="col-md-6 mt-2">

			<div class="input-group rounded">
				<button class="btn btn-info ml-auto mr-0 text-white" type="button" wire:click="nullinputs" data-toggle="modal" data-target="#officer_form">
					<i class="fas fa-plus"></i>
					Create Officer
				</button>
			</div>

		</div>
	</div>

	<div class="row">

		<div class="contents-container col-md-6 mb-2 table_student">
			@include('livewire.scholarship-officer.scholarship-officer-search-livewire')
		</div>

		<div class="contents-container col-md-6 info_officer collapse"  wire:ignore.self>
			@include('livewire.scholarship-officer.scholarship-officer-info-livewire')
		</div>

		<div class="creating_student_modal">
			<div wire:ignore.self class="modal fade officer_form" id="officer_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
					<form class="modal-content" wire:submit.prevent="save()">
						<div class="modal-header bg-dark text-white">
						<h5 class="modal-title" id="exampleModalCenterTitle">Officer Account {{ ((isset($officer_id))? 'Editing': 'Creating') }}</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true"><i class="fas fa-times-circle text-white"></i></span>
							</button>
						</div>
						<div class="modal-body student_creating">
							@include('livewire.scholarship-officer.scholarship-officer-form-livewire')
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

	</div>

	<script>
		window.addEventListener('officer-info', event => {
			$(".info_officer").collapse(event.detail.action);
		});

		window.addEventListener('officer-form', event => {
			$(".officer_form").modal(event.detail.action);
		});
	</script>
</div>
