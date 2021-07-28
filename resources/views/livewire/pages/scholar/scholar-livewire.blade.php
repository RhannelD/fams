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
				<button wire:click="$emitTo('scholar-edit-livewire', 'create')" class="btn btn-info ml-auto mr-0 text-white" type="button" data-toggle="modal" data-target="#scholar_form">
					<i class="fas fa-plus"></i>
					Create Scholar
				</button>
			</div>

		</div>
	</div>

	<div class="row">

		<div class="contents-container col-md-6 mb-2 table_student">
			@include('livewire.pages.scholar.scholar-search-livewire')
		</div>

		<div wire:ignore.self class="contents-container col-md-6 info_scholar collapse
			@isset( $user )
				show
			@endisset
			">
			@isset($user)
				@livewire('scholar-info-livewire', [$user], key('scholar-info-'.time().$user))
			@endisset
		</div>

		<div>
			<div wire:ignore.self class="modal fade" id="scholar_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
					@livewire('scholar-edit-livewire'))
				</div>
			</div>
		</div>

	</div>

	<script>
		window.addEventListener('scholar-info', event => {
			$(".info_scholar").collapse(event.detail.action);
		});

		window.addEventListener('scholar-form', event => {
			$("#scholar_form").modal(event.detail.action);
		});

		window.addEventListener('change-password-form', event => {
			$("#change_password_form").modal(event.detail.action);
		});
	</script>
</div>
