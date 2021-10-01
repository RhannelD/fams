<div>
    <div wire:ignore.self class="modal fade" id="unsubmitted-response" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="unsubmitted-response-label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="unsubmitted-response-label">Unsubmitted Response</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group rounded mt-1 mb-1">
                                <input type="search" class="form-control rounded" placeholder="Search Scholar Response" wire:model.debounce.1000ms='search'/>
                                <span wire:click="$emitSelf('refresh')" class="input-group-text border-0">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-1">
                                <div class="input-group mt-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">
                                            <i class="fas fa-sort"></i>
                                        </span>
                                    </div>
                                    <select wire:model='order_by' class="form-control" id="order_by">
                                        <option value="firstname">First name</option>
                                        <option value="lastname">Last name</option>
                                        <option value="email">Email</option>
                                        <option value="phone">Phone</option>
                                    </select>
                                    <div class="input-group-append">
                                        <select wire:model='order' class="input-group-text bg-white" id="order_by">
                                            <option value="asc">A-Z</option>
                                            <option value="desc">Z-A</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex bd-highlight my-1">
                        <div class="mr-auto bd-highlight py-1">
                            <div class="input-group">
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
                        </div>
                        @if (count($scholars))
                            <div class="py-1 bd-highlight">
                                {{ $scholars->links() }}
                            </div>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tr>
                                <th>
                                    #
                                </th>
                                <th>
                                    Firstname
                                    @if ( $order_by == 'firstname' )
                                        @include('livewire.main.table-thead-order-livewiwre')
                                    @endif
                                </th>
                                <th>
                                    Lastname
                                    @if ( $order_by == 'lastname' )
                                        @include('livewire.main.table-thead-order-livewiwre')
                                    @endif
                                </th>
                                <th>
                                    Email
                                    @if ( $order_by == 'email' )
                                        @include('livewire.main.table-thead-order-livewiwre')
                                    @endif
                                </th>
                                <th>
                                    Phonenumber
                                    @if ( $order_by == 'phone' )
                                        @include('livewire.main.table-thead-order-livewiwre')
                                    @endif
                                </th>
                            </tr>
                            @forelse ($scholars as $scholar)
                                <tr>
                                    <th>
                                        {{ ( ($loop->index + 1) + ( ($show_row * $page ) - $show_row) ) }}
                                    </th>
                                    <td>
                                        {{ $scholar->firstname }}
                                    </td>
                                    <td>
                                        {{ $scholar->lastname }}
                                    </td>
                                    <td>
                                        {{ $scholar->email }}
                                    </td>
                                    <td>
                                        {{ $scholar->phone }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">
                                        No results...
                                    </td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
