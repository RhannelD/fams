<div class="row">
    <div class="col-md-6">
        <div class="input-group rounded">
            <input type="search" class="form-control rounded" placeholder="Search Scholars" wire:model.debounce.1000ms='search'/>
            <span wire:click='$refresh' class="input-group-text ml-1 border-0">
                <i class="fas fa-search"></i>
            </span>
        </div>
        <div class="my-2">
            <div class="d-flex flex-wrap my-1">
                <h4 class="my-auto ml-0">
                    Add Recipients
                </h4>
                <div class="mr-0 ml-auto">
                    {{ $search_users->links() }}
                </div>
            </div> 
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <th>
                            #
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Phone
                        </th>
                        <th>
                            Category
                        </th>
                        <th>
                            Email
                        </th>
                    </thead>
                    <tbody>
                        @forelse ($search_users as $search_user)
                            <tr wire:click='add_recipient({{ $search_user->id }})'>
                                <th class='align-middle text-nowrap'>
                                    {{ ( ($loop->index + 1) + ( ($show_row * $page ) - $show_row) ) }}
                                </th>
                                <td class='align-middle text-nowrap'>
                                    {{ $search_user->flname() }}
                                </td>
                                <td class='align-middle text-nowrap'>
                                    {{ $search_user->phone }}
                                </td>
                                <td class='align-middle text-nowrap'>
                                    {{ $search_user->scholarship_scholar->category->category }}
                                </td>
                                <td class='align-middle text-nowrap'>
                                    {{ $search_user->email }}
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
    </div>
    <div class="col-md-6">
        <h4 class="mb-2">
            Recipient List
            {{ count($added_recipients)? "[".count($added_recipients)."]": '' }}
        </h4>
        @if ( count($added_recipients) )
            <div class="card card-body mt-2 p-2">
                <div class="d-flex flex-wrap">
                    @foreach ($added_recipients as $added_recipient)
                        <div class="border border-dark rounded px-2 py-1 mr-1 mb-1">
                            {{ $added_recipient->flname() }}
                            <a wire:click='remove_recipient({{ $added_recipient->id }})'>
                                <i class="far fa-times-circle"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="alert alert-info my-auto">
                Add a recipient
            </div>
        @endif
        <hr class="my-2">
        <div class="form-group mb-2">
            <label for="message">Enter Message</label>
            <textarea wire:model.lazy='message' class="form-control" id="message" rows="3" placeholder="Enter Message"></textarea>
            @error('message') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="d-flex justify-content-end">
            <button wire:click='send' wire:loading.attr='disabled' class="btn btn-success"
                @if ( !count($added_recipients) )
                    disabled
                @endif
                >
                <span wire:loading.remove wire:target='send'>
                    <i class="fas fa-sms"></i>
                    Send
                </span>
                <span wire:loading wire:target='send'>
                    <i class="fas fa-circle-notch fa-spin"></i>
                    Sending...
                </span>
            </button>
        </div>
    </div>
</div>