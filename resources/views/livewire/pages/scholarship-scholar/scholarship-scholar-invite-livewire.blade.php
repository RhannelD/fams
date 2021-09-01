<div class="modal-content">
    <div class="modal-header bg-dark text-white">
    <h5 class="modal-title" id="exampleModalCenterTitle">Scholar Invite</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fas fa-times-circle text-white"></i></span>
        </button>
    </div>
    <div class="modal-body pt-1" style="min-height: 300px;">
        <div class="row"> 
            <div class="col-12 pt-0">
                <ul wire:ignore class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="send-tab" data-toggle="tab" href="#send" role="tab" aria-controls="send" aria-selected="true">
                            Send invites
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="false">
                            Pending invites
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="accepted-tab" data-toggle="tab" href="#accepted" role="tab" aria-controls="accepted" aria-selected="false">
                            Accepted invites
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab" aria-controls="rejected" aria-selected="false">
                            Rejected invites
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div wire:ignore.self class="tab-pane fade show active pt-1" id="send" role="tabpanel" aria-labelledby="send-tab">
                        <div class="form-row">
                            <div class="col-md-4 order-md-last"> 
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select  wire:model="category_id" class="form-control" id="category">
                                        @forelse ($categories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->category }}
                                            </option>
                                        @empty
                                            <option>None</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name_email">Enter name or email</label>
                                    <input wire:model.debounce.1000ms="name_email" type="text" class="form-control" placeholder="Enter name or email" id="name_email">
                                </div>
                            </div>
                        </div>
                        @if (session()->has('message-success'))
                            <div class="alert alert-success">
                                {{ session('message-success') }}
                            </div>
                        @endif
                        @if(!$errors->has('name_email') && !empty($name_email) )
                            <h6 class="mb-1">Invite via email</h6>
                            <hr class="my-1">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control bg-white border-success" value="{{ $name_email }}" readonly>
                                <div class="input-group-append">
                                    <button wire:click="invite_email('{{ $name_email }}')" class="btn btn-success" type="button">
                                        Invite
                                    </button>
                                </div>
                            </div>
                        @endif
                
                        @isset($search_officers) 
                            <h6 class="mb-1">Officer results</h6>
                            <hr class="my-1">
                            @forelse ($search_officers as $officer)
                                <div class="input-group mb-1">
                                    <input type="text" class="form-control bg-white border-success" value="{{ $officer->flname() }} / {{ $officer->email }}" readonly>
                                    <div class="input-group-append">
                                        <button wire:click="invite_email('{{ $officer->email }}')" class="btn btn-success" type="button">
                                            Invite
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <input type="text" class="form-control bg-white" value="No results..." readonly>
                            @endforelse
                        @endisset
                    </div>
                    <div wire:ignore.self class="tab-pane fade pt-1" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                        @isset($pending_invites[0])
                            <div class="d-flex justify-content-end">
                                <button wire:click='cancel_all_invite_confirm' class="btn btn-danger text-white">
                                    Cancel all
                                </button>
                            </div>
                        @endisset
                        @forelse ($pending_invites as $invite)
                            <div class="input-group my-1">
                                <input type="text" class="form-control bg-white border-info" value="{{ $invite->email }}" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text border-info bg-white rounded-right">
                                        {{ $invite->category->category }}
                                    </span>
                                    <button wire:click="cancel_invite({{ $invite->id }})" wire:loading.attr="disabled" class="btn btn-danger ml-1 rounded" type="button">
                                        <i wire:loading.remove wire:target="cancel_invite({{ $invite->id }})" class="fas fa-times-circle"></i>
                                        <i wire:loading wire:target="cancel_invite({{ $invite->id }})" class="fas fa-spinner fa-spin"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <input type="text" class="form-control bg-white border-info mt-1" value="None" readonly>
                        @endforelse
                    </div>
                    <div wire:ignore.self class="tab-pane fade pt-1" id="accepted" role="tabpanel" aria-labelledby="accepted-tab">
                        @isset($accepted_invites[0])
                            <div wire:click='clear_all_accepted_invite_confirm' class="d-flex justify-content-end">
                                <button class="btn btn-info text-white">
                                    Clear all
                                </button>
                            </div>
                        @endisset
                        @forelse ($accepted_invites as $invite)
                            <div class="input-group my-1">
                                <input type="text" class="form-control bg-white border-success" value="{{ $invite->email }}" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text border-success bg-white rounded-right">
                                        {{ $invite->category->category }}
                                    </span>
                                    <button wire:click="cancel_invite({{ $invite->id }})" wire:loading.attr="disabled" class="btn btn-dark rounded ml-1" type="button">
                                        <i wire:loading.remove wire:target="cancel_invite({{ $invite->id }})" class="fas fa-minus-circle"></i>
                                        <i wire:loading wire:target="cancel_invite({{ $invite->id }})" class="fas fa-spinner fa-spin"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <input type="text" class="form-control bg-white border-info my-1" value="None" readonly>
                        @endforelse
                    </div>
                    <div wire:ignore.self class="tab-pane fade pt-1" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                        @isset($rejected_invites[0])
                            <div class="d-flex justify-content-end">
                                <button wire:click='resend_all_rejected_invite_confirm' class="btn btn-success text-white">
                                    Resend all
                                </button>
                                <button wire:click='clear_all_rejected_invite_confirm' class="btn btn-info text-white ml-1">
                                    Clear all
                                </button>
                            </div>
                        @endisset
                        @forelse ($rejected_invites as $invite)
                            <div class="input-group my-1">
                                <input type="text" class="form-control bg-white border-dark" value="{{ $invite->email }}" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text border-dark bg-white rounded-right">
                                        {{ $invite->category->category }}
                                    </span>
                                    <button wire:click='resend_rejected_invite({{ $invite->id }})' class="btn btn-success rounded-left ml-1" type="button">
                                        Resend
                                    </button>
                                    <button wire:click="cancel_invite({{ $invite->id }})" wire:loading.attr="disabled" class="btn btn-dark" type="button">
                                        <i wire:loading.remove wire:target="cancel_invite({{ $invite->id }})" class="fas fa-minus-circle"></i>
                                        <i wire:loading wire:target="cancel_invite({{ $invite->id }})" class="fas fa-spinner fa-spin"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <input type="text" class="form-control bg-white border-info mt-1" value="None" readonly>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>

        window.addEventListener('swal:confirm:delete_something', event => { 
            swal({
              title: event.detail.message,
              text: event.detail.text,
              icon: event.detail.type,
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                @this.call(event.detail.function)
              }
            });
        });

    </script>
</div>