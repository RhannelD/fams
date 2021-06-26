<div>

	<div class="row">

        <div class="col-12">

            @isset($scholarship_id)
                <div class="form-group">
                    <label for="c_sr_code">User ID</label>
                    <input type="text" class="form-control" id="c_sr_code" value="{{ $scholarship_id }}" disabled>
                </div>
            @endisset

            <div class="form-group">
                <label for="c_scholarship">Scholarship Program</label>
                <input type="text" wire:model.lazy="scholarship" class="form-control" id="c_scholarship" placeholder="Scholarship Program">
                @error('scholarship') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

        </div>

    </div>

</div>