<div>
    @livewire('add-ins.scholarship-program-livewire', [$scholarship_id, 'officers'], key('page-tabs-'.time().$scholarship_id))

	<div class="row mb-1 mx-1">
		<div class="input-group col-md-5 mt-2">
			<div class="input-group rounded mb-auto mt-1">
				<input type="search" class="form-control rounded" placeholder="Search Officer" wire:model.debounce.1000ms='search'/>
				<span wire:click="$emitSelf('refresh')" class="input-group-text border-0">
					<i class="fas fa-search"></i>
				</span>
			</div>
		</div>

        <div class="col-md-7 mt-2">
            <div class="d-flex flex-wrap">
                <div class="d-flex mt-1">
                    <div class="input-group my-0 mr-1">
                        <div class="input-group-prepend">
                          <label class="input-group-text" for="position">Position</label>
                        </div>
                        
                        <select wire:model="position" class="form-control" id="position">
                            <option value="">All</option>
                            <option value="1">Admin</option>
                            <option value="2">Officer</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex mt-1">
                    <div class="input-group my-0 mr-1">
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
                </div>

                @can('viewAny', [\App\Models\ScholarshipOfficerInvite::class, $this->scholarship_id])
                    <div class="form-group mr-0 ml-sm-auto my-0 mt-1">
                        <button class="form-control btn btn-success" type="button" data-toggle="modal" data-target="#officer_invite">
                            <i class="fas fa-envelope"></i>
                            Invite
                        </button>
                    </div>

                    <div>
                        <div wire:ignore.self class="modal fade officer_invite" id="officer_invite" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                @livewire('scholarship-officer.scholarship-officer-invite-livewire', [$scholarship_id]))
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
		</div>
	</div>

	<div class="row mx-1">
        @if (session()->has('message-success'))
            <div class="col-12 my-2">
                <div class="alert alert-success my-0">
                    {{ session('message-success') }}
                </div>
            </div>
        @endif
		<div class="contents-container col-12 mb-2">
			@include('livewire.pages.scholarship-officer.scholarship-officer-search-livewire')
		</div>
	</div>
</div>
