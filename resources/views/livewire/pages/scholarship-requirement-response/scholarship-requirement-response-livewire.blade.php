<div>
@isset($requirement)
    @livewire('add-ins.scholarship-program-livewire', [$requirement->scholarship_id, ($requirement->promote? 'applications': 'renewals')], key('page-tabs-'.time().$requirement->scholarship_id))

    <div class="row mx-1 mt-1">  
        <div class="col-12">
            <div class="card bg-primary border-primary shadow">
                <div class="card-body text-white border-primary py-1">
                    <h2 class="my-1 row">
                        @isset( $requirement->requirement )
                            <a href="{{ route('scholarship.requirement.open', [$requirement_id]) }}" class="text-white col-auto">
                                <strong>{{ $requirement->requirement }}</strong>
                            </a>
                        @endisset
                    </h2>
                </div>
            </div>
        </div>
    </div>

	<div class="row mb-1 mx-1">
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
                <div class="col-12 d-flex flex-wrap">
                    <div class="mt-1 mr-1">
                        @include('livewire.pages.scholarship-requirement-response.scholarship-requirement-response-filter-livewire')
                    </div>
                    @if ( !$requirement->promote )
                        <div class="btn-group mt-1 mr-1">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                More
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <button class="dropdown-item" type="button" data-toggle="modal" data-target="#unsubmitted-response">
                                    Not Yet Responding
                                </button>
                            </div>
                        </div>
                    @endif
                    @if ( is_null($index) )   
                        <div class="mt-1 d-flex">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <label class="input-group-text rounded-left" for="rows">Rows</label>
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
                        </div>
                    @endif
                    @isset($index)
                        <button class="btn btn-info mx-1 text-white mr-0 ml-sm-auto mt-1" wire:click="unview_response">
                            View Table
                        </button>
                    @endisset
                </div>
            </div>
        </div>
	</div>

    <div wire:ignore>
        @livewire('scholarship-requirement-response.scholarship-requirement-response-unsubmitted-livewire', [$requirement_id], key('unsubmitted-response-'.time()))
    </div>

	<div class="row mx-1">
        @if ( is_null($index))
            <div class="contents-container col-12 mb-2 table_responses">
                @include('livewire.pages.scholarship-requirement-response.scholarship-requirement-response-search-livewire')
            </div>
        @else
            @isset( $responses )
            <div class="contents-container col-12 mb-2 table_responses px-0">
                <hr class="my-1 d-md-none d-sm-block">
                <div class="d-flex flex-wrap my-1 mx-3">
                    <div class="card ml-sm-auto mx-1 mt-1">
                        <div class="card-body pb-1 pt-2 px-2">
                            <i class="fas fa-spinner fa-spin" wire:loading wire:target="change_index"></i>
                            <span wire:loading.remove wire:target="change_index">
                                {{ $index+1 }} /{{ $responses->count() }}
                            </span>
                        </div>
                    </div>
                    <div class="mr-0">
                        @if ( $index > 0 )
                            <button class="btn btn-info mx-1 text-white mt-1" wire:click="change_index(-1)" wire:loading.attr="disabled">
                                <i class="fas fa-chevron-circle-left"></i> Previous
                            </button>
                        @else
                            <button class="btn btn-dark mx-1 mt-1" disabled wire:click="change_index(0)">
                                <i class="fas fa-chevron-circle-left"></i> Previous
                            </button>
                        @endif

                        @if ( $index < $responses->count()-1 )
                            <button class="btn btn-info mx-1 text-white mt-1" wire:click="change_index(1)" wire:loading.attr="disabled">
                                Next <i class="fas fa-chevron-circle-right"></i>
                            </button>
                        @else
                            <button class="btn btn-dark mx-1 mt-1" disabled wire:click="change_index(0)">
                                Next <i class="fas fa-chevron-circle-right"></i>
                            </button>
                        @endif
                    </div>
                </div> 

                <hr class="my-1 mx-3">
                @isset($responses[$index]->id)
                    @livewire('scholarship-requirement-response.scholarship-requirement-response-view-livewire', [$responses[$index]->id], key('response-view-'.$requirement->scholarship_id))
                @endisset  
            </div>
            @endisset
        @endif
		
	</div>
@endisset    
</div>
