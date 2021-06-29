<div>

	<div class="row">

		<div class="col-lg-6">

            @isset($user_id)
                <div class="form-group">
                    <label for="c_sr_code">User ID</label>
                    <input type="text" class="form-control" id="c_sr_code" value="{{ $user_id }}" disabled>
                </div>
            @endisset

            <div class="form-group">
                <label for="c_firstname">First Name</label>
                <input type="text" wire:model.lazy="firstname" class="form-control" id="c_firstname" placeholder="First Name">
                @error('firstname') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="c_middlename">Middle Name</label>
                <input type="text" wire:model.lazy="middlename" class="form-control" id="c_middlename" placeholder="Middle Name">
                @error('middlename') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="c_lastname">Last Name</label>
                <input type="text" wire:model.lazy="lastname" class="form-control" id="c_lastname" placeholder="Last Name">
                @error('lastname') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div>
                <label>Gender {{ $gender }}</label>
                <select class="form-control" wire:model.lazy="gender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            
            <div class="form-group mt-3">
                <label for="c_birthday">Birth Date</label>
                <input type="date" wire:model.lazy="birthday" class="date form-control" id="c_birthday">
                @error('birthday') <span class="text-danger">{{ $message }}</span> @enderror	
            </div>

        </div>

        <div class="col-lg-6">

            <div class="form-group">
                <label for="c_email">Email Address</label>
                <input type="email" wire:model.lazy="email" class="form-control" id="c_email" placeholder="juan.delacruz.@g.batstate-u-edu.ph">
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="c_phone">Phone Number</label>
                <input type="text" wire:model.lazy="phone" class="form-control" id="c_phone" placeholder="09*********">
                @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="c_religion">Religion</label>
                <input type="text" wire:model.lazy="religion" class="form-control" id="c_religion" placeholder="Religion">
                @error('religion') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="c_birthplace">Birth Place</label>
                <input type="text" wire:model.lazy="birthplace" class="form-control" id="c_birthplace" placeholder="Birth Place">
                @error('birthplace') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            @if (!isset($user_id))
                <div class="form-group">
                    <label for="c_password">Password</label>
                    <input type="password" wire:model.lazy="password" class="form-control" id="c_password" placeholder="Password">
                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            @endif

        </div>

    </div>

</div>