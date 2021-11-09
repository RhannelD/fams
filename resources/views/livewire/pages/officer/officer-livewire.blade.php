<div>
	<nav class="navbar navbar-expand-lg navbar-dark bg-mid-bar border-bottom-0 pb-1">
		<div class="input-group col-md-6 mb-1 px-0">
			<div class="input-group rounded">
				<input type="search" class="form-control rounded btn-white border-white" placeholder="Search Officers" wire:model.debounce.1000ms='search'/>
				<span wire:click='$refresh' class="input-group-text bg-white border-0 ml-1">
					<i class="fas fa-search"></i>
				</span>
			</div>
		</div>

		<div class="col-md-6 mb-1 px-0">
			<div class="input-group rounded">
				<button wire:click="$emitTo('officer.officer-edit-livewire', 'create')" class="btn btn-secondary ml-auto mr-0" type="button" data-toggle="modal" data-target="#officer_form">
					<i class="fas fa-plus"></i>
					Create Officer
				</button>
			</div>
		</div>
	</nav>

	<div class="row mx-1">

		<div class="contents-container col-md-6 mb-2 table_student">
			@include('livewire.pages.officer.officer-search-livewire')
		</div>

		<div wire:ignore.self class="contents-container col-md-6 info_officer collapse
			@isset( $user )
				show
			@endisset
			">
			@isset($user)
				@livewire('officer.officer-info-livewire', [$user], key('officer-info-'.time().$user))
			@endisset
		</div>

	</div>

	<div>
        <div wire:ignore.self class="modal fade officer_form" id="officer_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                @livewire('officer.officer-edit-livewire'))
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
		
		window.addEventListener('change-password-form', event => {
			$("#change_password_form").modal(event.detail.action);
		});
	</script>
</div>
