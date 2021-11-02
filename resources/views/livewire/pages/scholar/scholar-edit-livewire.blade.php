<form class="modal-content" wire:submit.prevent="save()">
    <div class="modal-header bg-dark text-white">
    <h5 class="modal-title" id="exampleModalCenterTitle">Scholar Account {{ ((isset($user->id))? 'Editing': 'Creating') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fas fa-times-circle text-white"></i></span>
        </button>
    </div>
    <div class="modal-body student_creating">
        <h4>
            Scholar's Information
        </h4>
        @include('livewire.form.user-form-livewire')

        <hr class="my-2">
        <h4>
            Educational Information
        </h4>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="c_course">Course</label>
                    <select wire:model="user_info.course_id" class="form-control" id="c_course">
                        <option>Select Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->course }}</option>
                        @endforeach
                    </select>
                    @error('user_info.course_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="c_year">Year Level</label>
                    <select wire:model="user_info.year" class="form-control" id="c_year">
                        <option>Select Year</option>
                        <option value="1">1st Year</option>
                        <option value="2">2nd Year</option>
                        <option value="3">3rd Year</option>
                        <option value="4">4th Year</option>
                        <option value="5">5th Year</option>
                        <option value="6">6th Year</option>
                        <option value="7">7th Year</option>
                        <option value="8">8th Year</option>
                        <option value="9">9th Year</option>
                        <option value="10">10th Year</option>
                    </select>
                    @error('user_info.year') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="c_semester">Semester</label>
                    <select wire:model="user_info.semester" class="form-control" id="c_semester">
                        <option>Select Semester</option>
                        <option value="1">1st Semester</option>
                        <option value="2">2nd Semester</option>
                        <option value="3">Summer</option>
                    </select>
                    @error('user_info.semester') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <hr class="my-2">
        <h4>
            Family Information
        </h4>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="c_mother_name">Mother's Name</label>
                    <input wire:model.lazy="user_info.mother_name" type="text" class="form-control" id="c_mother_name" placeholder="Mother's Name">
                    @error('user_info.mother_name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group mt-3">
                    <label for="c_mother_birthday">Mother's Birth Date</label>
                    <input wire:model.lazy="user_info.mother_birthday" type="date" class="date form-control" id="c_mother_birthday">
                    @error('user_info.mother_birthday') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
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
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label for="c_father_name">Father's Name</label>
                    <input wire:model.lazy="user_info.father_name" type="text" class="form-control" id="c_father_name" placeholder="Father's Name">
                    @error('user_info.father_name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group mt-3">
                    <label for="c_father_birthday">Father's Birth Date</label>
                    <input wire:model.lazy="user_info.father_birthday" type="date" class="date form-control" id="c_father_birthday">
                    @error('user_info.father_birthday') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
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
        </div>

    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save" wire:loading.remove wire:target="save"></i>
            <i class="fas fa-spinner fa-spin" wire:loading wire:target="save"></i>
            Save
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-secondary" id="cancel_edit">
            <i class="fas fa-times"></i>
            Cancel
        </button>
    </div>
</form>
