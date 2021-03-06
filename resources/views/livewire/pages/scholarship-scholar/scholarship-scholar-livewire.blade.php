<div>
    @livewire('add-ins.scholarship-program-livewire', [$scholarship_id, 'scholars'], key('page-tabs-'.time().$scholarship_id))

	<div class="row mb-1 mx-2">
		<div class="input-group col-md-5 mt-2 mb-auto">
			<div class="input-group rounded">
				<input type="search" class="form-control rounded" placeholder="Search Scholars" wire:model.debounce.1000ms='search'/>
				<span wire:click="$emitSelf('refresh')" class="input-group-text border-0">
					<i class="fas fa-search"></i>
				</span>
			</div>
		</div>

        <div class="col-md-7 mt-2">
            <div class="d-flex flex-wrap">
                <div class="d-flex">
                    <div class="input-group my-0">
                        <div class="input-group-prepend">
                            <label class="input-group-text d-none d-sm-block" for="rows">Rows</label>
                            <label class="input-group-text d-block d-sm-none" for="rows">#</label>
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
                @if (Auth::user()->is_admin() || Auth::user()->is_officer())    
                    <div class="form-group my-0 ml-1 mr-auto">
                        @include('livewire.pages.scholarship-scholar.scholarship-scholar-filter-livewire')
                    </div>
                    <div class="form-group my-0 ml-1">
                        <button class="form-control btn btn-success" type="button" data-toggle="modal" data-target="#officer_invite">
                            Invite
                        </button>
                    </div>
                    <div class="form-group my-0 ml-1">
                        <a href="{{ route('scholar.list.pdf', [$scholarship_id]) }}" target="blank" class="form-control btn btn-primary">
                            Print
                        </a>
                    </div>
                    <div>
                        <div wire:ignore.self class="modal fade officer_invite" id="officer_invite" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                @livewire('scholarship-scholar.scholarship-scholar-invite-livewire', [$scholarship_id]))
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
	</div>

	<div class="row mx-2">
		<div class="contents-container col-12 mb-1">
			@include('livewire.pages.scholarship-scholar.scholarship-scholar-search-livewire')
		</div>
	</div>

    <script>
        window.addEventListener('swal:confirm:remove_scholar', event => { 
            swal({
                title: event.detail.message,
                text: event.detail.text,
                icon: event.detail.type,
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    @this.call(event.detail.function)
                }
            });
        });
    </script>
</div>
