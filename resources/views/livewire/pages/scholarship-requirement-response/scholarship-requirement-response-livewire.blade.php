<div>
@isset($requirement)
    @livewire('add-ins.scholarship-program-livewire', [$requirement->scholarship_id], key('page-tabs-'.time().$requirement->scholarship_id))

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

                @if ( is_null($index) )    
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
                @else
                    <div class="col-md-4 col-6 mb-0 mt-1"></div>
                @endif

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
		
        @if ( is_null($index))
            <div class="contents-container col-12 mb-2 table_responses">
                @include('livewire.pages.scholarship-requirement-response.scholarship-requirement-response-search-livewire')
            </div>
        @else
            @isset( $responses )
            <div class="contents-container col-12 mb-2 table_responses px-0">
                <div class="d-flex my-1 mx-3">
                    <div class="ml-0 mr-auto">
                        <button class="btn btn-info mx-1 text-white" wire:click="unview_response">
                            View Table
                        </button>
                    </div>
                    <div class="my-auto" wire:loading wire:target="change_index">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <div class="card mx-1">
                        <div class="card-body pb-1 pt-2 px-2">
                            {{ $index+1 }} /{{ $responses->count() }}
                        </div>
                    </div>
                    <div class="mr-0">
                        @if ( $index > 0 )
                            <button class="btn btn-info mx-1 text-white" wire:click="change_index(-1)" wire:loading.attr="disabled">
                                <i class="fas fa-chevron-circle-left"></i> Previous
                            </button>
                        @else
                            <button class="btn btn-dark mx-1" disabled wire:click="change_index(0)">
                                <i class="fas fa-chevron-circle-left"></i> Previous
                            </button>
                        @endif

                        @if ( $index < $responses->count()-1 )
                            <button class="btn btn-info mx-1 text-white" wire:click="change_index(1)" wire:loading.attr="disabled">
                                Next <i class="fas fa-chevron-circle-right"></i>
                            </button>
                        @else
                            <button class="btn btn-dark mx-1" disabled wire:click="change_index(0)">
                                Next <i class="fas fa-chevron-circle-right"></i>
                            </button>
                        @endif
                    </div>
                </div> 

                <hr class="my-1 mx-3">
                @isset($responses[$index]->id)
                    @livewire('scholarship-requirement-response.scholarship-requirement-response-view-livewire', [$responses[$index]->id], key('response-view-'.time().$requirement->scholarship_id))
                @endisset  
            </div>
            @endisset
        @endif
		
	</div>
@endisset    
</div>
