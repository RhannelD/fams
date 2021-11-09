<div>
    @livewire('add-ins.scholarship-program-livewire', [$scholarship_id, 'officers'], key('page-tabs-'.time().$scholarship_id))

	<nav class="navbar navbar-expand-lg navbar-dark bg-mid-bar border-bottom-0 pb-1">
		<div class="input-group col-md-6 mb-1 px-0">
			<div class="input-group rounded">
				<input type="search" class="form-control rounded btn-white border-white" placeholder="Search Officers" wire:model.debounce.1000ms='search'/>
				<span wire:click="$emitSelf('refresh')" class="input-group-text bg-white border-0 ml-1">
					<i class="fas fa-search"></i>
				</span>
			</div>
		</div>
	</nav>

	<div class="row mx-1">
		<div class="contents-container col-12 mb-2">
			@include('livewire.pages.scholarship-officer.scholarship-officer-search-livewire')
		</div>
	</div>
</div>
