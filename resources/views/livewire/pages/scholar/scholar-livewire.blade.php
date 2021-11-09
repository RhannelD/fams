<div>
	<nav class="navbar navbar-expand-lg navbar-dark bg-mid-bar border-bottom-0 pb-1">
		<div class="input-group col-md-6 mb-1 px-0">
			<div class="input-group rounded">
				<input type="search" class="form-control rounded btn-white border-white" placeholder="Search Scholars" wire:model.debounce.1000ms='search'/>
				<span wire:click='$refresh' class="input-group-text bg-white border-0 ml-1">
					<i class="fas fa-search"></i>
				</span>
			</div>
		</div>

		<div class="col-md-6 mb-1 px-0">
			<div class="input-group rounded">
				<button wire:click="$emitTo('scholar.scholar-edit-livewire', 'create')" class="btn btn-secondary ml-auto mr-0" type="button" data-toggle="modal" data-target="#scholar_form">
					<i class="fas fa-plus"></i>
					Create Scholar
				</button>
			</div>
		</div>
	</nav>

	<div class="row mx-1">

		<div class="contents-container col-md-6 mb-2 table_student">
			@include('livewire.pages.scholar.scholar-search-livewire')
		</div>

		<div wire:ignore.self class="contents-container col-md-6 info_scholar collapse
			@isset( $user )
				show
			@endisset
			">
			@isset($user)
				@livewire('scholar.scholar-info-livewire', [$user], key('scholar-info-'.time().$user))
			@endisset
		</div>

		<div>
			<div wire:ignore.self class="modal fade" id="scholar_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
					@livewire('scholar.scholar-edit-livewire'))
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
