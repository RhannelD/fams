<div>
	<div class="row">
        <div class="col-12">

            @isset($user_scholarship_position)
                <div class="form-group">
                    <label>Scholarship</label>
                    <input type="text" class="form-control" value="{{ $user_scholarship_position->scholarship }}" disabled>
                </div>

                <div class="form-group">
                    <label for="user_position">Position</label>
                    <select wire:model="user_scholarship_position.position_id" class="form-control" id="user_position">
                        @foreach ($positions as $position)
                            <option value="{{ $position->id }}" 
                                {{-- @if ($position->id == $user_scholarship_position->position_id)
                                    selected 
                                @endif --}}
                                >
                                {{ $position->position }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endisset

        </div>
    </div>
</div>