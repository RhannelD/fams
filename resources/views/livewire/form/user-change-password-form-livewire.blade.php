<div>

	<div class="row">

        <div class="col-12">

            @isset($user)
                <div class="form-group">
                    <label>Scholar</label>
                    <input type="text" class="form-control" value="{{ $user->firstname }} {{ $user->middlename }} {{ $user->lastname }}" disabled>
                </div>

                <div class="form-group">
                    <label for="change_password">Password</label>
                    <input type="password" wire:model.lazy="password" class="form-control" id="change_password" placeholder="Password">
                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            @endisset

        </div>

    </div>

</div>
