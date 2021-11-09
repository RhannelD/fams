<div class="modal-content">
    <div class="modal-header bg-dark text-white">
    <h5 class="modal-title" id="exampleModalCenterTitle">Scholar Invite</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fas fa-times-circle text-white"></i></span>
        </button>
    </div>
    <div class="modal-body pt-1" style="min-height: 300px;"  wire:poll.8000ms>
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
                            @if ( count($categories)>1 )
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
                            @else
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="name_email">Enter name or email</label>
                                        <input wire:model.debounce.1000ms="name_email" type="text" class="form-control" placeholder="Enter name or email" id="name_email">
                                    </div>
                                </div>    
                            @endif
                        </div>
                        @if (session()->has('message-success'))
                            <div class="alert alert-success">
                                {{ session('message-success') }}
                            </div>
                        @endif
                        @if (session()->has('message-error'))
                            <div class="alert alert-danger mt-0">
                                {{ session('message-error') }}
                            </div>
                        @endif
                        @empty($name_email)
                            <div class="alert alert-info">
                                Please enter the scholar's name or email.
                            </div>
                            <div class="alert alert-info">
                                Inviting a non-registered email will be requested for signing-up before accepting.
                            </div>
                        @endempty
                        @if(!$errors->has('name_email') && !empty($name_email) )
                            <h6 class="mb-1">Invite via email</h6>
                            <hr class="my-1">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control bg-white border-success" value="{{ $name_email }}" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-success" type="button"
                                        wire:click="invite_email('{{ $name_email }}')" 
                                        wire:loading.attr='disabled'
                                        >
                                        <i class="fas fa-envelope" id="invite_email_{{ $name_email }}"
                                            wire:loading.remove 
                                            wire:target="invite_email('{{ $name_email }}')"
                                            >
                                        </i>
                                        <i class="fas fa-spinner fa-spin" id="invite_email_load_{{ $name_email }}"
                                            wire:loading 
                                            wire:target="invite_email('{{ $name_email }}')">
                                        </i>
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
                                        <button class="btn btn-success" type="button"
                                            wire:click="invite_email('{{ $officer->email }}')" 
                                            wire:loading.attr='disabled'
                                            >
                                            <i class="fas fa-envelope" id="invite_email_{{ $officer->email }}"
                                                wire:loading.remove 
                                                wire:target="invite_email('{{ $officer->email }}')">
                                            </i>
                                            <i class="fas fa-spinner fa-spin" id="invite_email_load_{{ $officer->email }}"
                                                wire:loading 
                                                wire:target="invite_email('{{ $officer->email }}')">
                                            </i>
                                            Invite
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <input type="text" class="form-control bg-white" value="No results..." readonly>
                            @endforelse
                        @endisset

                        <div class="alert alert-success my-3">
                            You may also 
                            <a href="{{ route('scholarship.scholar.invite', [$scholarship_id]) }}">
                                import excel file
                            </a>.
                        </div>
                    </div>
                    <div wire:ignore.self class="tab-pane fade pt-1" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                        @isset($pending_invites[0])
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-danger text-white"
                                    wire:click='cancel_all_invite_confirm'
                                    wire:loading.attr='disabled'
                                    wire:target='cancel_all_invites'
                                    >
                                    <i class="fas fa-spinner fa-spin"
                                        wire:loading
                                        wire:target='cancel_all_invites'
                                        >
                                    </i>
                                    Cancel all
                                </button>
                            </div>
                        @endisset
                        @forelse ($pending_invites as $invite)
                            <div class="input-group my-1">
                                <input type="text" class="form-control bg-white border-info" 
                                    value="{{ $invite->email }}" readonly>
                                <div class="input-group-append">
                                    @if ( count($categories)>1 )
                                        <span class="input-group-text border-info bg-white">
                                            {{ $invite->category->category }}
                                        </span>
                                    @endif
                                    @if ( !isset($invite->sent) )
                                        <span class="input-group-text border-info bg-white rounded-right text-info">
                                            <i class="fas fa-circle-notch fa-spin"></i>
                                        </span>
                                    @elseif ( $invite->sent )
                                        <span class="input-group-text border-info bg-white rounded-right text-success">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                    @else
                                        <span class="input-group-text border-info bg-white text-danger">
                                            <i class="fas fa-exclamation-circle"></i>
                                        </span>
                                        <button class="btn btn-success rounded-right" type="button"
                                            wire:click='resend_rejected_invite({{ $invite->id }})' 
                                            wire:loading.attr="disabled"
                                            >
                                            <i class="fas fa-sync-alt" id="resend_rejected_invite_load_{{ $invite->id }}"
                                                wire:loading.class="fa-spin"
                                                wire:target="resend_rejected_invite('{{ $invite->id }}')">
                                        </i>
                                    </button>
                                    @endif
                                    <button class="btn btn-danger ml-1 rounded" type="button"
                                        wire:click="cancel_invite({{ $invite->id }})" 
                                        wire:loading.attr="disabled"
                                        >
                                        <i class="fas fa-times-circle" id="cancel_invite_icon_{{ $invite->id }}"
                                            wire:loading.remove 
                                            wire:target="cancel_invite('{{ $invite->id }}')"
                                            >
                                        </i>
                                        <i class="fas fa-spinner fa-spin" id="cancel_invite_load_{{ $invite->id }}"
                                            wire:loading 
                                            wire:target="cancel_invite('{{ $invite->id }}')">
                                        </i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <input type="text" class="form-control bg-white border-info mt-1" value="None" readonly>
                        @endforelse
                    </div>
                    <div wire:ignore.self class="tab-pane fade pt-1" id="accepted" role="tabpanel" aria-labelledby="accepted-tab">
                        @isset($accepted_invites[0])
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-info text-white"
                                    wire:click='clear_all_accepted_invite_confirm'
                                    wire:loading.attr='disabled'
                                    wire:target='clear_all_accepted_invite'
                                    >
                                    <i class="fas fa-spinner fa-spin"
                                        wire:loading
                                        wire:target='clear_all_accepted_invite'
                                        >
                                    </i>
                                    Clear all
                                </button>
                            </div>
                        @endisset
                        @forelse ($accepted_invites as $invite)
                            <div class="input-group my-1">
                                <input type="text" class="form-control bg-white border-success {{ count($categories)<2? 'rounded': '' }}" 
                                    value="{{ $invite->email }}" readonly>
                                <div class="input-group-append">
                                    @if ( count($categories)>1 )
                                        <span class="input-group-text border-success bg-white rounded-right">
                                            {{ $invite->category->category }}
                                        </span>
                                    @endif
                                    <button class="btn btn-dark rounded ml-1" type="button"
                                        wire:click="cancel_invite({{ $invite->id }})" 
                                        wire:loading.attr="disabled" 
                                        >
                                        <i class="fas fa-minus-circle" id="cancel_invite_icon_{{ $invite->id }}"
                                            wire:loading.remove
                                            wire:target="cancel_invite('{{ $invite->id }}')"
                                            >
                                        </i>
                                        <i class="fas fa-spinner fa-spin" id="cancel_invite_load_{{ $invite->id }}"
                                            wire:loading 
                                            wire:target="cancel_invite('{{ $invite->id }}')">
                                        </i>
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
                                <button class="btn btn-success text-white"
                                    wire:click='resend_all_rejected_invite_confirm'
                                    wire:loading.attr='disabled' 
                                    wire:target='resend_all_rejected_invite'
                                    >
                                    <i class="fas fa-spinner fa-spin"
                                        wire:loading
                                        wire:target='resend_all_rejected_invite'
                                        >
                                    </i>
                                    Resend all
                                </button>
                                <button class="btn btn-info text-white ml-1"
                                    wire:click='clear_all_rejected_invite_confirm'
                                    wire:loading.attr='disabled' 
                                    wire:target='clear_all_rejected_invite'
                                    >
                                    <i class="fas fa-spinner fa-spin"
                                        wire:loading
                                        wire:target='clear_all_rejected_invite'
                                        >
                                    </i>
                                    Clear all
                                </button>
                            </div>
                        @endisset
                        @forelse ($rejected_invites as $invite)
                            <div class="input-group my-1">
                                <input type="text" class="form-control bg-white border-dark {{ count($categories)<2? 'rounded': '' }}" 
                                    value="{{ $invite->email }}" readonly>
                                <div class="input-group-append">
                                    @if ( count($categories)>1 )
                                        <span class="input-group-text border-dark bg-white rounded-right">
                                            {{ $invite->category->category }}
                                        </span>
                                    @endif
                                    <button class="btn btn-success rounded-left ml-1" type="button"
                                        wire:click='resend_rejected_invite({{ $invite->id }})' 
                                        wire:loading.attr="disabled"
                                        >
                                        <i class="fas fa-spinner fa-spin" id="resend_rejected_invite_load_{{ $invite->id }}"
                                            wire:loading 
                                            wire:target="resend_rejected_invite('{{ $invite->id }}')">
                                        </i>
                                        Resend
                                    </button>
                                    <button class="btn btn-dark" type="button"
                                        wire:click="cancel_invite({{ $invite->id }})" 
                                        wire:loading.attr="disabled" 
                                        >
                                        <i class="fas fa-minus-circle" id="cancel_invite_icon_{{ $invite->id }}"
                                            wire:loading.remove
                                            wire:target="cancel_invite('{{ $invite->id }}')"
                                            >
                                        </i>
                                        <i class="fas fa-spinner fa-spin" id="cancel_invite_load_{{ $invite->id }}"
                                            wire:loading 
                                            wire:target="cancel_invite('{{ $invite->id }}')">
                                        </i>
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
