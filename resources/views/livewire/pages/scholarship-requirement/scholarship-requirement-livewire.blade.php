<div>
    @livewire('add-ins.scholarship-program-livewire', [$scholarship_id, 'requirements'], key('page-tabs-'.time().$scholarship_id))

	<div class="row mb-1 mx-1">
		<div class="input-group col-md-5 mt-2">
			<div class="input-group rounded">
				<input type="search" class="form-control rounded" placeholder="Search Requirement" wire:model.debounce.1000ms='search'/>
				<span wire:click="$emitSelf('refresh')" class="input-group-text border-0">
					<i class="fas fa-search"></i>
				</span>
			</div>
		</div>

        <div class="col-md-7 mt-2">
            <div class="form-row">
                <div class="input-group col-5 my-0">
                    <select wire:model="promote" class="form-control" id="category">
                        <option value="null">All Requirements</option>
                        <option value="1">Application</option>
                        <option value="0">Renewal</option>
                    </select>
                </div>
                <div class="input-group col-4 my-0">
                    <div class="input-group-prepend">
                      <label class="input-group-text" for="rows">Rows</label>
                    </div>
                    <select wire:model="show_row" class="form-control" id="rows">
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="150">150</option>
                        <option value="200">200</option>
                    </select>
                </div>
                <div class="form-group col-3  my-0">
                    <button wire:click="create_requirement" class="form-control btn btn-success">
                        Create
                    </button>
                </div>
            </div>
		</div>
	</div>
    <div>
        @if (session()->has('deleted'))
            <div class="alert alert-info mt-2 mb-0">
                {{ session('deleted') }}
            </div>
        @endif
    </div>
	<div class="row mx-1">

		<div class="contents-container col-12 mb-2">
			@include('livewire.pages.scholarship-requirement.scholarship-requirement-search-livewire')
		</div>

	</div>
</div>
