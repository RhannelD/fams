<div>
    <div wire:ignore.self class="modal fade" id="email_sent_modal" tabindex="-1" aria-labelledby="email_sent_modal-label" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="email_sent_modal-label">Email Sent</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div wire:loading.delay wire:target='set_email_send' class="w-100 my-3">
                        <div class="alert alert-info">
                            <i class="fas fa-circle-notch fa-spin"></i>
                            Loading ...
                        </div>
                    </div>
                    <div wire:loading.remove wire:target='set_email_send' class="row">
                    @isset($email_send)
                        <div class="col-md-6" wire:poll.8000ms>
                            <h5>Sent Message</h5>
                            <p class="border border-primary rounded p-2">
                                {!! nl2br(e($email_send->message)) !!}
                            </p>
                            <div class="table-responsive overflow-auto" style="max-height: 420px;">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <th>
                                            Recipient
                                        </th>
                                        <th>
                                            Email 
                                        </th>
                                        <th class="text-center">
                                            Sent
                                        </th>
                                        <th>
                                        </th>
                                    </thead>
                                    <tbody>
                                        @foreach ($email_send->sent_to as $sent_to)
                                            <tr>
                                                <td class="align-middle text-nowrap">
                                                    {{ isset($sent_to->user)? $sent_to->user->flname(): '' }}
                                                </td>
                                                <td class="align-middle">
                                                    {{ $sent_to->email }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    @if ( is_null($sent_to->sent) )
                                                        <span class="text-info my-auto">
                                                            <i class="fas fa-circle-notch fa-spin"></i>
                                                        </span>
                                                    @elseif ( $sent_to->sent )
                                                        <span class="text-success my-auto">
                                                            <i class="fas fa-check-circle"></i>
                                                        </span>
                                                    @else
                                                        <span class="text-danger">
                                                            <i class="fas fa-times-circle"></i>
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center align-middle">
                                                    @if ( !$sent_to->sent && $sent_to->user )
                                                        <button class="btn btn-success btn-sm text-nowrap" id="resend-email-to-{{ $sent_to->user->id }}"
                                                            wire:click='send({{ $sent_to->user->id }})' 
                                                            wire:loading.attr='disabled'
                                                            >
                                                            <span id="resend-email-to-send-{{ $sent_to->user->id }}"
                                                                wire:loading.remove wire:target='send({{ $sent_to->user->id }})'
                                                                >
                                                                <i class="fas fa-envelope"></i>
                                                                Resend
                                                            </span>
                                                            <span id="resend-email-loading-to-{{ $sent_to->user->id }}"
                                                                wire:loading wire:target='send({{ $sent_to->user->id }})'
                                                                >
                                                                <i class="fas fa-circle-notch fa-spin"></i>
                                                                Sending
                                                            </span>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <hr class="my-2 d-block d-md-none">
                            <h5>Send to other scholar</h5>
                            <div class="d-flex flex-wrap">
                                <div class="d-flex">
                                    <div class="input-group rounded">
                                        <input type="search" class="form-control rounded" placeholder="Search Scholars" wire:model.debounce.1000ms='search'/>
                                        <span wire:click='$refresh' class="input-group-text ml-1 border-0">
                                            <i class="fas fa-search"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="mr-0 ml-auto">
                                    {{ $search_users->links() }}
                                </div>
                            </div>
                            <div class="table-responsive mt-2">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <th>
                                            User
                                        </th>
                                        <th>
                                            Email 
                                        </th>
                                        <th>
                                        </th>
                                    </thead>
                                    <tbody>
                                        @forelse ($search_users as $search_user)
                                            <tr>
                                                <td class="align-middle text-nowrap">
                                                    {{ $search_user->flname() }}
                                                </td>
                                                <td class="align-middle text-nowrap">
                                                    {{ $search_user->email }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    <button class="btn btn-success btn-sm text-nowrap" id="send-email-to-{{ $search_user->id }}"
                                                        wire:click='send({{ $search_user->id }})' 
                                                        wire:loading.attr='disabled'
                                                        >
                                                        <span id="send-email-to-send-{{ $search_user->id }}"
                                                            wire:loading.remove wire:target='send({{ $search_user->id }})'
                                                            >
                                                            <i class="fas fa-envelope"></i>
                                                            Send
                                                        </span>
                                                        <span id="send-email-loading-to-{{ $search_user->id }}"
                                                            wire:loading wire:target='send({{ $search_user->id }})'
                                                            >
                                                            <i class="fas fa-circle-notch fa-spin"></i>
                                                            Sending
                                                        </span>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8">
                                                    No results...
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endisset
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                </div>
            </div>
        </div>
    </div>

    <script>
        function set_email_send($email_send_id) {
            if ( @this.email_send_id != $email_send_id ) {
                @this.set_email_send($email_send_id);
            }
        }

        window.addEventListener('view_set_email_send', event => {
            $('#email_sent_modal').modal('show');
            set_email_send(event.detail.email_send_id);
        });
    </script>
</div>
