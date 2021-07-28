<div>
	<div class="row mb-1">
		<div class="input-group col-md-6 mt-2">

			<div class="input-group rounded">
				<input type="search" class="form-control rounded" placeholder="Search Officers" wire:model.debounce.1000ms='search'/>
				<span class="input-group-text border-0">
					<i class="fas fa-search"></i>
				</span>
			</div>

		</div>

		<div class="col-md-6 mt-2">

			<div class="input-group rounded">
				<button wire:click="$emitTo('officer-edit-livewire', 'create')" class="btn btn-info ml-auto mr-0 text-white" type="button" data-toggle="modal" data-target="#officer_form">
					<i class="fas fa-plus"></i>
					Create Officer
				</button>
			</div>

		</div>
	</div>

	<div class="row">

		<div class="contents-container col-md-6 mb-2 table_student">
			@include('livewire.pages.officer.officer-search-livewire')
		</div>

		<div class="contents-container col-md-6 info_officer collapse"  wire:ignore.self>
			@if ($user)
				@livewire('officer-info-livewire', [$user], key('officer-info-'.time().$user))
			@endif
		</div>

	</div>

	<div>
        <div wire:ignore.self class="modal fade officer_form" id="officer_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                @livewire('officer-edit-livewire'))
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
