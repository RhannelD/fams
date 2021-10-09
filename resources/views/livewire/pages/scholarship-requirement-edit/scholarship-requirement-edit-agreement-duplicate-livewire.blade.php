<div>
    <div wire:ignore.self class="modal fade" id="agreementDuplicationModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="agreementDuplicationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agreementDuplicationModalLabel">Duplicate Another Agreements</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="min-height: 300px;">
                    <ul wire:ignore class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="searchs-tab" data-toggle="tab" href="#searchs" role="tab" aria-controls="searchs" aria-selected="true">Search</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="view-tab" data-toggle="tab" href="#view" role="tab" aria-controls="view" aria-selected="false">View</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div wire:ignore.self class="tab-pane fade show active" id="searchs" role="tabpanel" aria-labelledby="searchs-tab">
                            <div class="row">
                                <div class="input-group col-md-6 mt-2">
                                    <div class="input-group rounded">
                                        <input type="search" class="form-control rounded" placeholder="Search Requirement" wire:model.debounce.1000ms='search'/>
                                        <span wire:click="$emitSelf('refresh')" class="input-group-text border-0">
                                            <i class="fas fa-search"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="input-group offset-md-2 col-8 col-sm-6 col-md-4 mt-2">
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
                            <div class="row mt-2">
                                <div class="col-12 table-responsive">
                                    <table class="table table-hover table-sm" style="min-width: 400px;">
                                        <thead>
                                            <tr>
                                                <th scope="col">Requirement</th>
                                                <th scope="col">For</th>
                                                <th scope="col">State</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($requirements as $requirement)
                                                <tr wire:click='view_requirement({{ $requirement->id }})'>
                                                    <td>
                                                        {{ $requirement->requirement }}
                                                    </td>
                                                    <td>
                                                        @if ($requirement->promote)
                                                            <span class="badge badge-pill badge-secondary">Application</span>
                                                        @else
                                                            <span class="badge badge-pill badge-primary">Renewal</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @switch( $requirement->can_be_accessed() )
                                                            @case('finished')
                                                                <span class="badge badge-pill badge-danger">Finished</span>
                                                                @break

                                                            @case('ongoing')
                                                                <span class="badge badge-pill badge-success">Ongoing</span>
                                                                @break

                                                            @case('disabled')
                                                                <span class="badge badge-pill badge-dark">Disabled</span>
                                                                @break
                                                        @endswitch
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <th colspan="6">None</th>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div wire:ignore.self class="tab-pane fade" id="view" role="tabpanel" aria-labelledby="view-tab">
                            @if ( isset($duplicate_agreement_requirement) )
                            <div class="mt-1">
                                <label class="mb-1">Requirement:</label>
                                <h4>
                                    {{ $duplicate_agreement_requirement->requirement }}
                                </h4>
                                <label class="mb-1">Agreements:</label>
                                <p class="mb-1">
                                    {!! Purify::clean($duplicate_agreement_requirement->agreements->first()->agreement) !!}
                                </p>
                                <hr class="my-2">
                                <div class="d-flex justify-content-end">
                                    <button wire:click='duplication_confirm' class="btn btn-success">
                                        Duplicate Now
                                    </button>
                                </div>
                            </div>
                            @else
                                <div class="alert alert-info mt-2">
                                    Please select first a requirement.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('change:tab.view', event => {
            $('#view-tab').tab('show');
        });
        
		window.addEventListener('agreement-duplicate-form', event => {
			$("#agreementDuplicationModal").modal(event.detail.action);
		});
        
        window.addEventListener('swal:confirm:duplicate_agreement', event => { 
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
