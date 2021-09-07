<div>
    <button class="form-control btn btn-primary btn-block d-flex" type="button" data-toggle="modal" data-target="#filter-modal">
        <i class="fas fa-filter my-auto ml-auto mr-auto mr-md-1"></i>
        <span class="d-none d-sm-block ml-1 mr-auto">
            Filter
        </span>
    </button>

    <div wire:ignore.self class="modal fade" id="filter-modal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Search</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="category">Approval</label>
                        <select wire:model="approval" class="form-control" id="category">
                            <option value="">All</option>
                            <option value="1">Approved</option>
                            <option value="2">Denied</option>
                            <option value="3">Pending</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="order_by">Order By</label>
                        <div class="input-group mb-3">
                            <select wire:model='order_by' class="form-control mr-1 rounded" id="order_by">
                                <option value="firstname">First name</option>
                                <option value="lastname">Last name</option>
                                <option value="email">Email</option>
                                <option value="submit_at">Submitted Date</option>
                            </select>
                            <div class="input-group-append">
                                <select wire:model='order' class="form-control rounded" id="order_by">
                                    <option value="asc">A-Z</option>
                                    <option value="desc">Z-A</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click='clear_filter' type="button" class="btn btn-success">Clear Filter</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
