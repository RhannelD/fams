<div>
    <button class="form-control btn btn-primary d-flex" type="button" data-toggle="modal" data-target="#filter-modal">
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
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="acad_year">Academic Year</label>
                            <select wire:model="acad_year" id="acad_year" class="form-control">
                                @for ($year = $max_acad_year; $year>2016; $year--)
                                    <option value="{{ $year }}">
                                        {{ $year }}-{{ $year+1 }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="acad_semester">Semester</label>
                            <select wire:model="acad_sem" id="acad_semester" class="form-control">
                                <option value="1">First Semester</option>
                                <option value="2">Second Semester</option>
                            </select>
                        </div>
                    </div>
                    <hr class="my-2">
                    @if ( count($categories)>1 )
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select wire:model="category_id" class="form-control" id="category">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category['id'] }}">{{ $category['category'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="category"># of other scholarship</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <select wire:model='comparision' class="form-control rounded-0">
                                    <option value="">None</option>
                                    <option value="=">=</option>
                                    <option value="<"><</option>
                                    <option value=">">></option>
                                    <option value="<="><=</option>
                                    <option value=">=">>=</option>
                                </select>
                            </div>
                            <input wire:model.lazy='num_scholarship' type="number" class="form-control" min="1" max="20" value="1">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="order_by">Order By</label>
                        <div class="input-group mb-3">
                            <select wire:model='order_by' class="form-control" id="order_by">
                                <option value="firstname">First name</option>
                                <option value="lastname">Last name</option>
                                <option value="email">Email</option>
                                <option value="phone">Phone</option>
                            </select>
                            <div class="input-group-append">
                                <select wire:model='order' class="form-control" id="order_by">
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
