<div>
@isset($requirement)
    @livewire('scholarship-program-livewire', [$requirement->scholarship_id], key('page-tabs-'.time().$requirement->scholarship_id))

    <hr class="mb-1">
    <div class="row">  
        <div class="col-12">
            <div class="card bg-primary border-primary shadow">
                <div class="card-body text-white border-primary py-1">
                    <h2 class="my-1">
                        @isset( $requirement->requirement )
                            <strong>{{ $requirement->requirement }}</strong>
                        @endisset
                    </h2>
                </div>
            </div>
        </div>
    </div>

	<div class="row mb-1">
		<div class="input-group col-md-5 mt-2">
			<div class="input-group rounded mt-1">
				<input type="search" class="form-control rounded" placeholder="Search Scholar Response" wire:model.debounce.1000ms='search'/>
				<span wire:click="$emitSelf('refresh')" class="input-group-text border-0">
					<i class="fas fa-search"></i>
				</span>
			</div>
		</div>

        <div class="col-md-7 mt-2">
            <div class="form-row">
                <div class="input-group col-md-4 col-6 mb-0 mt-1">
                    <div class="input-group-prepend">
                    <label class="input-group-text" for="category">Approval</label>
                    </div>
                    <select wire:model="approval" class="form-control" id="category">
                        <option value="">All</option>
                        <option value="1">Approved</option>
                        <option value="2">Denied</option>
                        <option value="3">Pending</option>
                    </select>
                </div>
                <div class="input-group col-md-4 col-6 mb-0 mt-1">
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
                <div class="input-group col-md-4 col-sm-12 mb-0 mt-1 d-flex">
                    <div class="btn-group mr-0 ml-auto">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            More Actions
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <button class="dropdown-item" type="button">Action</button>
                            <button class="dropdown-item" type="button">Another action</button>
                            <button class="dropdown-item" type="button">Something else here</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>

	<div class="row">
		<div class="contents-container col-12 mb-2 table_responses">
            @if ( is_null($index))
			    @include('livewire.pages.scholarship-requirement-response.scholarship-requirement-response-search-livewire')
            @else
			    @include('livewire.pages.scholarship-requirement-response.scholarship-requirement-response-view-livewire')
            @endif
		</div>
	</div>
@endisset    
</div>
