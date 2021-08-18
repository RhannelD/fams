<div>
	<div class="row mb-1">
		<div class="input-group col-md-5 mt-2">

			<div class="input-group rounded">
				<input type="search" class="form-control rounded" placeholder="Search Officer" wire:model.debounce.1000ms='search'/>
				<span wire:click="$emitSelf('refresh')" class="input-group-text border-0">
					<i class="fas fa-search"></i>
				</span>
			</div>

		</div>

        <div class="col-md-7 mt-2">
            <div class="form-row">
                <div class="input-group col-5 my-0">
                    <div class="input-group-prepend">
                      <label class="input-group-text" for="position">Position</label>
                    </div>
                    <select wire:model="position" class="form-control" id="position">
                        <option value="">All</option>
                        <option value="1">Admin</option>
                        <option value="2">Officer</option>
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
                @if (Auth::user()->usertype != 'scholar')    
                    <div class="form-group col-3  my-0">
                        <button class="form-control btn btn-success">
                            Invite
                        </button>
                    </div>
                @endif
            </div>
		</div>
	</div>

	<div class="row">

		<div class="contents-container col-12 mb-2">
			@include('livewire.pages.scholarship-officer.scholarship-officer-search-livewire')
		</div>

	</div>
</div>
