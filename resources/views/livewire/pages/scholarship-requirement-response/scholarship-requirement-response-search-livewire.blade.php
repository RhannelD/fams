<div>   
    <div class="d-flex justify-content-end my-1">
        {{ $responses->links() }}
    </div> 

    <div class="card table-responsive">
        <table class="table table-sm table-hover card-header response_table">
            <thead class="thead-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Submitted Date</th>
                    <th class="text-center">Approval</th>
                </tr>
            </thead>

            <tbody>
                @forelse($responses as $response)
                    <tr class="rows">
                        <td class="text-nowrap">{{ $response->user->flname() }}</td>
                        <td class="text-nowrap">{{ $response->user->email }}</td>
                        <td class="text-nowrap">{{ $response->submit_at }}</td>
                        <td class="text-nowrap text-center">
                            @if ( is_null($response->approval) )
                                <span class="badge badge-pill badge-info">Pending</span>
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