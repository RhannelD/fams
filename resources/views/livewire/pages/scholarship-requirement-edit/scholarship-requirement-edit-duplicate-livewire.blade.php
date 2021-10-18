<div>
    <div class="card shadow mb-2 requirement-item-hover">
        <div class="card-body">
            <div class="alert alert-danger">
                Duplication will remove all progress then copies the selected requirement!
            </div>
            <button class="btn btn-block btn-dark text-white" type="button" data-toggle="modal" data-target="#duplicationModal">
                Duplicate
            </button>
        </div>
    </div>
    <hr>

    <div wire:ignore.self class="modal fade" id="duplicationModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="duplicationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="duplicationModalLabel">
                        Requirement Duplication
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-2">
                    <ul wire:ignore class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="search-tab" data-toggle="tab" href="#search" role="tab" aria-controls="search" aria-selected="true">Search</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="selected-tab" data-toggle="tab" href="#selected" role="tab" aria-controls="selected" aria-selected="false">Selected Requirement</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div wire:ignore.self class="tab-pane fade show active" id="search" role="tabpanel" aria-labelledby="search-tab">
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

                        <div wire:ignore.self class="tab-pane fade" id="selected" role="tabpanel" aria-labelledby="selected-tab">
                            @if ( isset($duplicate_requirement) )
                                <div class="mt-1">
                                    <label class="mb-1">Requirement:</label>
                                    <h4>
                                        {{ $duplicate_requirement->requirement }}
                                    </h4>
                                    <label class="mb-1">Desciption:</label>
                                    <p class="mb-1">
                                        {!! Purify::clean($duplicate_requirement->description) !!}
                                    </p>
                                    <hr class="my-1">
                                    @isset($duplicate_requirement->categories->first()->category->category)    
                                        <p class="mb-1">
                                            Scholar Category:
                                            <strong>
                                                {{ $duplicate_requirement->categories->first()->category->category }}
                                            </strong>
                                        </p>
                                    @endisset
                                    <p>
                                        For:
                                        @if ($requirement->promote)
                                            <span class="badge badge-pill badge-secondary">Application</span>
                                        @else
                                            <span class="badge badge-pill badge-primary">Renewal</span>
                                        @endif
                                        State:
                                        @switch( $requirement->can_be_accessed() )
                                            @case('ongoing')
                                                <span class="badge badge-pill badge-success">Ongoing</span>
                                                @break

                                            @case('disabled')
                                                <span class="badge badge-pill badge-dark">Disabled</span>
                                                @break
                                        @endswitch
                                    </p>
                                    <hr class="my-2">
                                    <div wire:click='duplication_confirm' class="d-flex justify-content-end">
                                        <button class="btn btn-success">
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
        window.addEventListener('change:tab.selected', event => {
            $('#selected-tab').tab('show');
        });
        
		window.addEventListener('duplicate-form', event => {
			$("#duplicationModal").modal(event.detail.action);
		});
        
        window.addEventListener('swal:confirm:duplicate_requirement', event => { 
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
