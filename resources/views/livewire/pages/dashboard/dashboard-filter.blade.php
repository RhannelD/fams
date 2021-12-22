<button class="btn btn-outline-light mr-1" data-toggle="modal" data-target="#filter_option">
    <i class="fas fa-filter"></i>
    Filter
</button>
<div wire:ignore.self class="modal fade" id="filter_option" tabindex="-1" aria-labelledby="filter_optionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filter_optionLabel">Filter Options</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="input_scholarship">Scholarship</label>
                    <select wire:model="scholarship_id" id="input_scholarship" class="form-control">
                        <option value="">All Scholarships</option>
                        @foreach ($scholarships as $scholarship)
                            <option value="{{ $scholarship->id }}">
                                {{ $scholarship->scholarship }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="input_year">Academic Filter by</label>
                        <select wire:model="filter_line_year" id="input_year" class="form-control">
                            <option value="1">Year</option>
                            <option value="0">Semester</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="input_span">        
                            @if ($filter_line_year)
                                Year
                            @else
                                Semester
                            @endif
                            Span
                        </label>
                        <input wire:model="filter_span" type="number" id="input_span" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>