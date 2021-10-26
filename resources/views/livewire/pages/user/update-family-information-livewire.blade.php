<div>
    <div wire:ignore.self class="modal fade" id="update-family-info-modal" tabindex="-1" aria-labelledby="update-family-info-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="update-family-info-label">
                        <strong>Update Family Information</strong>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Mother's Information</h5>
                    <div class="form-group">
                        <label for="c_mother_occupation">Mother's Occupation</label>
                        <input wire:model.lazy="user_info.mother_occupation" type="text" class="form-control" id="c_mother_occupation" placeholder="Mother's Occupation">
                        @error('user_info.mother_occupation') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="c_mother_educational_attainment">Mother's Educational Attainment</label>
                        <input wire:model.lazy="user_info.mother_educational_attainment" type="text" class="form-control" id="c_mother_educational_attainment" placeholder="Mother's Educational Attainment">
                        @error('user_info.mother_educational_attainment') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <input wire:model.lazy="user_info.mother_living" class="form-check-input" type="radio" name="c_mother_living" id="c_mother_living_1" value="1">
                            <label class="form-check-label" for="c_mother_living_1">Living</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input wire:model.lazy="user_info.mother_living" class="form-check-input" type="radio" name="c_mother_living" id="c_mother_living_2" value="0">
                            <label class="form-check-label" for="c_mother_living_2">Deceased</label>
                        </div>
                        @error('user_info.mother_living') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <hr class="my-2">
                    <h5>Father's Information</h5>
                    <div class="form-group">
                        <label for="c_father_occupation">Father's Occupation</label>
                        <input wire:model.lazy="user_info.father_occupation" type="text" class="form-control" id="c_father_occupation" placeholder="Father's Occupation">
                        @error('user_info.father_occupation') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="c_father_educational_attainment">Father's Educational Attainment</label>
                        <input wire:model.lazy="user_info.father_educational_attainment" type="text" class="form-control" id="c_father_educational_attainment" placeholder="Father's Educational Attainment">
                        @error('user_info.father_educational_attainment') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <input wire:model="user_info.father_living" class="form-check-input" type="radio" name="c_father_living" id="c_father_living_1" value="1">
                            <label class="form-check-label" for="c_father_living_1">Living</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input wire:model="user_info.father_living" class="form-check-input" type="radio" name="c_father_living" id="c_father_living_2" value="0">
                            <label class="form-check-label" for="c_father_living_2">Deceased</label>
                        </div>
                        @error('user_info.father_living') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id='save-update-info'
                        wire:click='save'
                        wire:loading.attr='disabled'
                        >
                        <i id="save-update-info-load" class="fas fa-spinner fa-spin" 
                            wire:loading
                            wire:target='save'
                            >
                        </i>
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
