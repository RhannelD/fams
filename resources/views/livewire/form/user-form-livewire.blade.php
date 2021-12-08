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
                <div class="form-row mt-0">
                    <div class="form-group col mt-0">
                        <label for="c_barangay">Barangay</label>
                        <input type="text" wire:model.lazy="user.barangay" class="form-control" id="c_barangay" placeholder="Barangay">
                        @error('user.barangay') <span class="text-danger">{{ $message }}</span> @enderror	
                    </div>
                    <div class="form-group col mt-0">
                        <label for="c_municipality">Municipality</label>
                        <input type="text" wire:model.lazy="user.municipality" class="form-control" id="c_municipality" placeholder="Municipality">
                        @error('user.municipality') <span class="text-danger">{{ $message }}</span> @enderror	
                    </div>
                    <div class="form-group col mt-0">
                        <label for="c_province">Province</label>
                        <input type="text" wire:model.lazy="user.province" class="form-control" id="c_province" placeholder="Province">
                        @error('user.province') <span class="text-danger">{{ $message }}</span> @enderror	
                    </div>
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
                <div class="form-row mt-0">
                    <div class="form-group col mt-0">
                        <label for="c_barangay">Barangay</label>
                        <input type="text" wire:model.lazy="user.barangay" class="form-control" id="c_barangay" placeholder="Barangay">
                        @error('user.barangay') <span class="text-danger">{{ $message }}</span> @enderror	
                    </div>
                    <div class="form-group col mt-0">
                        <label for="c_municipality">Municipality</label>
                        <input type="text" wire:model.lazy="user.municipality" class="form-control" id="c_municipality" placeholder="Municipality">
                        @error('user.municipality') <span class="text-danger">{{ $message }}</span> @enderror	
                    </div>
                    <div class="form-group col mt-0">
                        <label for="c_province">Province</label>
                        <input type="text" wire:model.lazy="user.province" class="form-control" id="c_province" placeholder="Province">
                        @error('user.province') <span class="text-danger">{{ $message }}</span> @enderror	
                    </div>
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