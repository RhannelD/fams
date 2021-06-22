<div class="p-0 full-height p-sm-3 p-md-5 p-1 full-height">
    <div class="card border-0 full-height overflow-hidden login-panel shadow ">
        <div class="row mx-0 full-height">
            <div class="offset-md-5 col-md-7 offset-lg-7 col-lg-5 bg-white full-height">
                <div class="row full-height align-items-center">
                    <div class="offset-md-1 col-md-10">

                        <img src="{{ asset('img/scholarship-icon.png') }}" alt="" height="100px" class="mx-auto d-block mb-5">

                        <form class="col-12 mb-5" wire:submit.prevent="signin()">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control" id="email" wire:model.lazy="email" placeholder="user.email@example.com" required autocomplete="email" autofocus>
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" wire:model.lazy="password" required placeholder="Password">
                                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <hr>
                        
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-2">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Login
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>



