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
		<div class="input-group col-md-4 mt-2">
			<div class="input-group rounded mt-1">
				<input type="search" class="form-control rounded" placeholder="Search Scholar Response" wire:model.debounce.1000ms='search'/>
				<span wire:click="$emitSelf('refresh')" class="input-group-text border-0">
					<i class="fas fa-search"></i>
				</span>
			</div>
		</div>

        <div class="col-md-8 mt-2">
            <div class="form-row">
                <div class="col-9 col-md-9 col-xl-11 d-flex">
                    <div class="mt-1 mr-1">
                        @include('livewire.pages.scholarship-requirement-response.scholarship-requirement-response-filter-livewire')
                    </div>
                    <div class="btn-group mt-1 mr-1">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            More
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <button class="dropdown-item" type="button">
                                Another action
                            </button>
                            <button class="dropdown-item" type="button">
                                Something else here
                            </button>
                        </div>
                    </div>
                    @if ( is_null($index) )    
                        <div class="input-group mt-1">
                            <div class="input-group-prepend">
                                <label class="input-group-text rounded-left d-none d-lg-block" for="rows">Rows</label>
                                <label class="input-group-text rounded-left d-block d-lg-none" for="rows">#</label>
                            </div>
                            <select wire:model="show_row" class="form-control" id="rows" style="max-width: 100px;">
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="150">150</option>
                                <option value="200">200</option>
                            </select>
                        </div>
                    @endif
                </div>
                <div class="col-3 col-md-3 col-xl-1 d-flex">
                    <a href="{{ route('scholarship.requirement.open', [$requirement_id]) }}" class="btn btn-dark mt-1 mr-1 ml-auto">
                        Back
                    </a>
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
