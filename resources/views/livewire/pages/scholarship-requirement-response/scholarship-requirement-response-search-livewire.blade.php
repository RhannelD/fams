<div>   
    <div class="d-flex justify-content-end my-1">
        {{ $responses->links() }}
    </div> 

    <div class="table-responsive">
        <table class="table table-sm table-hover response_table">
            <thead class="">
                <tr>
                    <th>#</th>
                    <th>
                        Firstname
                        @if ( $order_by == 'firstname' )
                            @include('livewire.main.table-thead-order-livewiwre')
                        @endif
                    </th>
                    <th>
                        Lastname
                        @if ( $order_by == 'lastname' )
                            @include('livewire.main.table-thead-order-livewiwre')
                        @endif
                    </th>
                    <th>
                        Email
                        @if ( $order_by == 'email' )
                            @include('livewire.main.table-thead-order-livewiwre')
                        @endif
                    </th>
                    <th>
                        Submitted Date
                        @if ( $order_by == 'submit_at' )
                            @include('livewire.main.table-thead-order-livewiwre')
                        @endif
                    </th>
                    <th class="text-center">
                        Approval
                    </th>
                </tr>
            </thead>

            <tbody>
                @forelse($responses as $key_index => $response)
                    <tr class="rows" wire:click='view_response({{ $key_index }})'>
                        <th>
                            {{ ( ($loop->index + 1) + ( ($show_row * $page ) - $show_row) ) }}
                        </th>
                        <td class="text-nowrap">
                            {{ $response->user->firstname }}
                        </td>
                        <td class="text-nowrap">
                            {{ $response->user->lastname }}
                        </td>
                        <td class="text-nowrap">
                            {{ $response->user->email }}
                        </td>
                        <td class="text-nowrap">
                            {{ date('d-m-Y h:i A', strtotime($response->submit_at)) }}
                        </td>
                        <td class="text-nowrap text-center">
                            @if ( is_null($response->approval) )
                                <span class="badge badge-pill badge-info text-white">Pending</span>
                            @elseif ($response->approval)
                                <span class="badge badge-pill badge-success">Approved</span>
                            @else
                                <span class="badge badge-pill badge-danger">Denied</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="rows">
                        <td class="text-nowrap" colspan="4">
                            No Results
                        </td>
                    </tr>
                @endforelse    
            </tbody>
        </table> 
    </div>
</div>
