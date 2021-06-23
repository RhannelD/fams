<div>
	<div class="row mb-1">
		<div class="input-group col-md-6 mt-2">

			<div class="input-group rounded">
				<input type="search" class="form-control rounded" placeholder="Search Scholars" wire:model.debounce.1000ms='search'/>
				<span class="input-group-text border-0">
					<i class="fas fa-search"></i>
				</span>
			</div>

		</div>

		<div class="col-md-6 mt-2">

			<div class="input-group rounded">
				<button class="btn btn-info ml-auto mr-0" type="button" data-toggle="modal" data-target="#create_student">
					<i class="fas fa-plus"></i>
					Create Scholar
				</button>
			</div>

		</div>
	</div>

	<div class="row">

		<div class="contents-container col-md-6 main-tablebar mb-2 table_student">
			@include('livewire.scholar.scholar_search')
		</div>

		<div class="contents-container col-md-6 info_student collapse">

		</div>

		<div class="creating_student_modal">

			<div class="modal fade" id="create_student" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
					<div class="modal-content">
						<div class="modal-header bg-dark text-white">
						<h5 class="modal-title" id="exampleModalCenterTitle">Student Creation</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true"><i class="fas fa-times-circle text-white"></i></span>
							</button>
						</div>
						<div class="modal-body student_creating">
							
						</div>
					</div>
				</div>
			</div>

		</div>

	</div>
</div>
