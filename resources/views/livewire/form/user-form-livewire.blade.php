<div>

	<div class="row">

		<div class="col-lg-6">

            <div class="form-group">
                <label for="c_firstname">First Name</label>
                <input type="text" wire:model.lazy="user.firstname" class="form-control" id="c_firstname" placeholder="First Name">
                @error('user.firstname') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="c_middlename">Middle Name</label>
                <input type="text" wire:model.lazy="user.middlename" class="form-control" id="c_middlename" placeholder="Middle Name">
                @error('user.middlename') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="c_lastname">Last Name</label>
                <input type="text" wire:model.lazy="user.lastname" class="form-control" id="c_lastname" placeholder="Last Name">
                @error('user.lastname') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div>
                <label>Sex</label>
                <select class="form-control" wire:model.lazy="user.gender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
                @error('user.gender') <span class="text-danger">{{ $message }}</span> @enderror	
            </div>
            
            <div class="form-group mt-3">
                <label for="c_birthday">Birth Date</label>
                <input type="date" wire:model.lazy="user.birthday" class="date form-control" id="c_birthday">
                @error('user.birthday') <span class="text-danger">{{ $message }}</span> @enderror	
            </div>

            @if (!isset($user_id))
                <div class="form-group mt-3">
                    <label for="c_address">Address</label>
                    <input type="text" wire:model.lazy="user.address" class="form-control" id="c_address" placeholder="Address">
                    @error('user.address') <span class="text-danger">{{ $message }}</span> @enderror	
                </div>
            @endif

        </div>

        <div class="col-lg-6">

            <div class="form-group">
                <label for="c_email">Email Address</label>
                <input type="email" wire:model.lazy="user.email" class="form-control" id="c_email" placeholder="juan.delacruz.@g.batstate-u-edu.ph"
                    @isset($disable_email)
                        readonly
                    @endisset
                    >
                @error('user.email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="c_phone">Phone Number</label>
                <input type="text" wire:model.lazy="user.phone" class="form-control" id="c_phone" placeholder="09*********">
                @error('user.phone') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            @isset($user_id)
                <div class="form-group mt-3">
                    <label for="c_address">Address</label>
                    <input type="text" wire:model.lazy="user.address" class="form-control" id="c_address" placeholder="Address">
                    @error('user.address') <span class="text-danger">{{ $message }}</span> @enderror	
                </div>
            @endisset

            <div class="form-group">
                <label for="c_religion">Religion</label>
                <input type="text" wire:model.lazy="user.religion" class="form-control" id="c_religion" placeholder="Religion">
                @error('user.religion') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="c_birthplace">Birth Place</label>
                <input type="text" wire:model.lazy="user.birthplace" class="form-control" id="c_birthplace" placeholder="Birth Place">
                @error('user.birthplace') <span class="text-danger">{{ $message }}</span> @enderror
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