<div>
	<div class="row mb-1 mx-1">
		<div class="input-group col-md-6 mt-2">

			<div class="input-group rounded">
				<input type="search" class="form-control rounded" placeholder="Search Courses" wire:model.debounce.1000ms='search'/>
				<span wire:click='$refresh' class="input-group-text border-0">
					<i class="fas fa-search"></i>
				</span>
			</div>

		</div>

		<div class="col-md-6 mt-2">
			<div class="input-group rounded">
				<button onclick="unset_course()" class="btn btn-info ml-auto mr-0 text-white" type="button" data-toggle="modal" data-target="#course-modal">
					<i class="fas fa-plus"></i>
					Create Course
				</button>
			</div>
		</div>
	</div>

	<div class="row mx-1">
		<div class="contents-container col-12">
			@include('livewire.pages.course.course-search-livewire')
		</div>
	</div>

    @livewire('course.course-edit-livewire'))

	<script>
		window.addEventListener('course-modal', event => {
			$("#course-modal").modal(event.detail.action);
		});

		window.addEventListener('swal:confirm:delete_course', event => { 
            swal({
				title: event.detail.message,
				text: event.detail.text,
				icon: event.detail.type,
				buttons: true,
				dangerMode: true,
            }).then((willDelete) => {
				if (willDelete) {
					@this.delete_course(event.detail.course_id)
				}
            });
        });
	</script>
</div>
