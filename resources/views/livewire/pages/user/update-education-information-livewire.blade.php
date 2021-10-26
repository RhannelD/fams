<div>
    <div wire:ignore.self class="modal fade" id="update-education-info-modal" tabindex="-1" aria-labelledby="update-education-info-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="update-education-info-label">
                        <strong>Update Education Information</strong>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="c_school">School</label>
                                <select wire:model="user_info.school_id" class="form-control" id="c_school">
                                    <option>Select School</option>
                                    @foreach ($schools as $school)
                                        <option value="{{ $school->id }}">{{ $school->school }}</option>
                                    @endforeach
                                </select>
                                @error('user_info.school_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
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
