<div>
    @livewire('add-ins.scholarship-program-livewire', [$scholarship_id, 'officers'], key('page-tabs-'.time().$scholarship_id))

	<div class="row mb-1 mx-1">
		<div class="input-group col-md-6 mt-2">
			<div class="input-group rounded mb-auto mt-1">
				<input type="search" class="form-control rounded" placeholder="Search Officer" wire:model.debounce.1000ms='search'/>
				<span wire:click="$emitSelf('refresh')" class="input-group-text border-0">
					<i class="fas fa-search"></i>
				</span>
			</div>
		</div>
	</div>

	<div class="row mx-1">
		<div class="contents-container col-12 mb-2">
			@include('livewire.pages.scholarship-officer.scholarship-officer-search-livewire')
		</div>
	</div>
</div>
