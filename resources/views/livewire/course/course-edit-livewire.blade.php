<div>
    <div wire:ignore.self class="modal fade" id="course-modal" tabindex="-1" aria-labelledby="course-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="course-modal-label">Course {{ ((isset($course_id))? 'Editing': 'Creating') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info w-100" wire:loading.delay wire:target='set_course, unset_course'>
                        <i class="fas fa-circle-notch fa-spin"></i>
                        Loading...
                    </div>
                    <div class="form-group" wire:loading.remove wire:target='set_course, unset_course'>
                        <label for="course">Course</label>
                        <input wire:model.lazy='course.course' type="text" class="form-control" id="course" placeholder="Course">
                        @error('course.course') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" 
                        wire:click='save'
                        wire:loading.attr='disabled'
                        >
                        <i class="fas fa-circle-notch fa-spin" wire:loading wire:target='save'></i>
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function set_course($course_id) {
            @this.set_course($course_id);
        }
        function unset_course() {
            @this.unset_course();
        }
    </script>
</div>
