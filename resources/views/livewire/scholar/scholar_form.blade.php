<div>

	<div class="row">

		<div class="col-lg-6">

            @isset($scholar_id)
                <div class="form-group">
                    <label for="c_student_sr_code">ID</label>
                    <input type="text" class="form-control" id="c_student_sr_code" value="{{ $scholar_id }}" disabled>
                </div>
            @endisset

            <div class="form-group">
                <label for="c_student_firstname">Firstname</label>
                <input type="text" wire:model.lazy="firstname" class="form-control" id="c_student_firstname" placeholder="Firstname">
                @error('firstname') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="c_student_middlename">Middlename</label>
                <input type="text" wire:model.lazy="middlename" class="form-control" id="c_student_middlename" placeholder="Middlename">
                @error('middlename') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="c_student_lastname">Lastname</label>
                <input type="text" wire:model.lazy="lastname" class="form-control" id="c_student_lastname" placeholder="Lastname">
                @error('lastname') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div>
                <label>Gender</label>
                <div class="form-control">
                    <div class="form-check form-check-inline">
                        <input wire:model.lazy="gender" class="form-check-input c_student_gender" type="radio" id="male" value="male">
                        <label class="form-check-label" for="male">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input wire:model.lazy="gender" class="form-check-input c_student_gender" type="radio" id="female" value="female">
                        <label class="form-check-label" for="female">Female</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">

            <div class="form-group">
                <label for="c_student_email">Email</label>
                <input type="email" wire:model.lazy="email" class="form-control" id="c_student_email" placeholder="juan.delacruz.@g.batstate-u-edu.ph" value="">
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="c_student_phone">Phonenumber</label>
                <input type="text" wire:model.lazy="phone" class="form-control" id="c_student_phone" placeholder="juan.delacruz.@g.batstate-u-edu.ph" value="">
                @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="c_student_birthday">Birthday</label>
                <input type="date" wire:model.lazy="birthday" class="date form-control" id="c_student_birthday">
                @error('birthday') <span class="text-danger">{{ $message }}</span> @enderror	
            </div>

            @if (!isset($scholar_id))
                <div class="form-group">
                    <label for="c_student_password">Password</label>
                    <input type="password" wire:model.lazy="password" class="form-control" id="c_student_password" placeholder="Password">
                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            @endif
            @error('password') <span class="text-danger">{{ $message }}</span> @enderror

        </div>

    </div>

</div>