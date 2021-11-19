<div>
    @if (session()->has('message-success'))
        <div class="alert alert-success">
            {{ session('message-success') }}
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="d-flex flex-wrap my-1">
                <div class="d-flex">
                    <div class="input-group rounded">
                        <input type="search" class="form-control rounded" placeholder="Search" wire:model.debounce.1000ms='search'/>
                        <span wire:click='$refresh' class="input-group-text ml-1 border-0">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
                <div class="mr-0 ml-auto">
                    {{ $send_email_list->links() }}
                </div>
            </div> 
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="my-2">
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead class="thead-dark">
                            <th>
                                #
                            </th>
                            <th>
                                Sender
                            </th>
                            <th>
                                Message
                            </th>
                            <th class="text-center">
                                Sent
                            </th>
                            <th class="text-center">
                                Failed
                            </th>
                            <th class="text-center">
                                Date
                            </th>
                            <th></th>
                        </thead>
                        <tbody>
                            @forelse ($send_email_list as $send_email)
                                <tr class="border-bottom">
                                    <th class="align-middle">
                                        {{ ( ($loop->index + 1) + ( ($show_row * $page ) - $show_row) ) }}
                                    </th>
                                    <td class="align-middle text-nowrap">
                                        {{ $send_email->user->flname() }}
                                    </td>
                                    <td class="overflow-hidden align-middle text-nowrap" style="max-width: 200px;">
                                        {{ $send_email->message }}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ $send_email->sent_to->where('sent', true)->whereNotNull('sent')->count() }}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ $send_email->sent_to->where('sent', false)->whereNotNull('sent')->count() }}
                                    </td>
                                    <td class="text-center align-middle text-nowrap">
                                        {{ \Carbon\Carbon::parse($send_email->created_at)->format('M d, Y  h:i A') }}
                                    </td>
                                    <td class="text-center text-nowrap">
                                        <button class="btn btn-sm btn-info text-white" type="button" data-toggle="modal" data-target="#email_sent_modal"
                                            onclick="set_email_send({{ $send_email->id }})"
                                            >
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                        @can('deleteSendEmails', $scholarship)
                                            <button class="btn btn-sm btn-danger"
                                                wire:click='delete_send_email_confirm({{ $send_email->id }})'
                                                wire:target='delete_send_email_confirm, delete_send_email'
                                                wire:loading.attr='disabled'
                                                >
                                                <span wire:loading.remove wire:target="delete_send_email({{ $send_email->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                                <span wire:loading wire:target="delete_send_email({{ $send_email->id }})">
                                                    <i class="fas fa-circle-notch fa-spin"></i>
                                                </span>
                                            </button>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="8">
                                    No results...
                                </td>
                            </tr>
                            @endforelse
                            @if ($has_more)
                                <tr>
                                    <td colspan="8" class="p-0">
                                        <button wire:click="$set('show_row', {{ $show_row+8 }})" 
                                            class="btn btn-primary rounded-0 btn-block">
                                            <span wire:loading.remove wire:target="$set('show_row', {{ $show_row+8 }})">
                                                <i class="fas fa-chevron-down"></i>
                                                More
                                            </span>
                                            <span wire:loading wire:target="$set('show_row', {{ $show_row+8 }})">
                                                <i class="fas fa-circle-notch fa-spin"></i>
                                                Loading...
                                            </span>
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        
        window.addEventListener('swal:confirm:delete_send_email', event => { 
            swal({
              title: event.detail.message,
              text: event.detail.text,
              icon: event.detail.type,
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                @this.delete_send_email(event.detail.send_email_id)
              }
            });
        });

    </script>
</div>
