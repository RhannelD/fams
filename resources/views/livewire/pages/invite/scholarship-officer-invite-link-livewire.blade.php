<div>
   <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-info">
      <a class="navbar-brand text-light" href="{{ route('index') }}">
         <img src="{{ asset('img/scholarship-icon.png') }}" width="30" height="30" alt="" style="margin-top: -5px;">
         <Strong>
            {{ config('app.name') }}
         </Strong>
      </a>
   </nav>
   
   <div class="container-fluid">
   @isset($invite)
      <div class="row">
         <div class="col-12 my-2">
            <div class="card mb-3 border-info shadow item-hover mx-auto mw1k">
               <div class="card-header bg-primary text-white py-2">
                  <h3 class="my-0">
                     <strong>
                        {{ $invite->scholarship->scholarship }}
                     </strong>
                  </h3>
               </div>
               <div class="card-body">
                  <h5 class="my-0">
                     You have been invited as a officer to this scholarship program.
                  </h5>
               </div>
            </div>
         </div>
      </div>
   
      <div class="row">
         <div class="col-12 mb-2">
            @if ( isset($invite->user->id) )
               @if ( $user_id )
                  <div class="alert alert-success mw1k mx-auto">
                     Account Successfully Created.
                  </div>
               @endif
               <div class="alert alert-success my-4 mx-auto mw1k">
                  Please 
                  <span>
                     <a href="{{ route('index') }}">
                        login
                     </a>
                  </span>
                  on your account to respond!
               </div>
            @elseif ( Auth::check() )
               <div class="alert alert-info my-4 mx-auto mw1k">
                  Logout first
               </div>
            @elseif ( !$is_verify_email )
               <div class="card mb-3 border-info shadow item-hover mx-auto mw1k">
                  <div class="card-header bg-primary text-white">
                     <h5 class="my-0">
                        <strong>
                           Email Verication
                        </strong>
                     </h5>
                  </div>
                  <div class="card-body">
                     <div class="row">
                        <div class="col-12">
                           <h5>
                              Email: 
                              {{ $invite->email }}
                           </h5>
                        </div>
   
                        <div class="col-12 col-sm-6 col-md-4 my-2 py-0">
                           <div class="form-group my-0">
                              <div class="input-group mb-0">
                                 <input wire:model.defer='code' type="text" pattern="\d*" maxlength="6" class="form-control" placeholder="Code">
                                 <div class="input-group-append">
                                    <button wire:loading.attr='disabled' class="btn btn-success" type="button"
                                       wire:click='verify_code' 
                                       wire:loading.attr='disabled'
                                       >
                                       <i class="fas fa-spinner fa-spin"
                                           wire:loading
                                           wire:target='verify_code'
                                           >
                                       </i>
                                       Confirm
                                    </button>
                                 </div>
                               </div>
                               @error('code') <span class="text-danger">{{ $message }}</span> @enderror
                           </div>
                        </div> 
                        
                        <div class="col-12 my-0 d-md-flex">
                           <button class="btn btn-info text-white mr-2 my-1" type="button"
                              wire:click='resend_code'
                              wire:loading.attr='disabled'
                              >
                              <i class="fas fa-spinner fa-spin"
                                  wire:loading
                                  wire:target='resend_code'
                                  >
                              </i>
                              Re-send Code
                           </button>
                           @if (session()->has('message-success'))
                              <div class="alert alert-success py-2 my-1">
                                   {{ session('message-success') }}
                               </div>
                           @endif
                           @if (session()->has('message-error'))
                              <div class="alert alert-danger py-2 my-1">
                                   {{ session('message-error') }}
                               </div>
                           @endif
                        </div> 
                     </div>
                  </div>
               </div>
            @else  
               <form wire:submit.prevent="save" class="card mb-3 border-info shadow item-hover mx-auto mw1k">
                  <div class="card-header bg-primary text-white">
                     <h5 class="my-0">
                        Officer Account Creation
                     </h5>
                  </div>
                  <div class="card-body">
                     @include('livewire.form.user-form-livewire')
                     <hr class="my-2">
                     <div class="row">
                        <div class="col-lg-6">
                           <div class="form-group">
                              <label for="c_password">Password</label>
                              <input type="password" wire:model.lazy="password" class="form-control" id="c_password" placeholder="Password">
                              @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                           </div>
                        </div>
                        <div class="col-lg-6">
                           <div class="form-group">
                              <label for="c_password_confirm">Confirm Password</label>
                              <input type="password" wire:model.lazy="password_confirm" class="form-control" id="c_password_confirm" placeholder="Confirm Password">
                              @error('password_confirm') <span class="text-danger">{{ $message }}</span> @enderror
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card-footer d-flex justify-content-end">
                     <button type="submit" class="btn btn-success">
                         <i class="fas fa-save" wire:loading.remove wire:target="save"></i>
                         <i class="fas fa-spinner fa-spin" wire:loading wire:target="save"></i>
                         Submit
                     </button>
                  </div>
               </form>
            @endif   
         </div>
      </div>
   
   @endisset
   </div>
</div>
