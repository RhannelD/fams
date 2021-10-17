<div>
    <div class="d-flex align-content-start flex-wrap my-1">
        <div class="d-flex mr-0 mr-sm-auto">
            <div class="input-group rounded">
                <input type="search" class="form-control rounded" placeholder="Search Requirements" wire:model.debounce.1000ms='search'/>
                <span wire:click="$refresh" class="input-group-text border-0 mx-1">
                    <i class="fas fa-search"></i>
                </span>
            </div>
        </div>
        {{ $responses->links() }}
    </div> 

    <div class="table-responsive">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>#</th>
                    <th>Scholarship</th>
                    <th>Requirement</th>
                    <th class="text-center px-1">Target</th>
                    <th class="text-center px-1">State</th>
                    <th class="text-center px-1">Approved</th>
                    <th></th>
                </tr>
                @forelse ($responses as $response)
                    <tr>
                        <th>
                            {{ ( ($loop->index + 1) + ( ($show_row * $page ) - $show_row) ) }}
                        </th>
                        <td>
                            {{ $response->requirement->scholarship->scholarship }}
                        </td>
                        <td>
                            {{ $response->requirement->requirement }}
                        </td>
                        <td class="text-center px-1">
                            @if ($response->requirement->promote)
                                <span class="badge badge-pill badge-secondary">Application</span>
                            @else
                                <span class="badge badge-pill badge-primary">Renewal</span>
                            @endif
                        </td>
                        <td class="text-center px-1">
                            @switch( $response->requirement->can_be_accessed() )
                                @case('finished')
                                    <span class="badge badge-pill badge-danger">Finished</span>
                                    @break

                                @case('ongoing')
                                    <span class="badge badge-pill badge-success">Ongoing</span>
                                    @break

                                @case('disabled')
                                    <span class="badge badge-pill badge-dark">Disabled</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="text-center px-1">
                            @if ( is_null($response->submit_at) )
                                <span class="badge badge-pill badge-secondary">Not submitted...</span>
                            @elseif ( is_null($response->approval) )
                                <span class="badge badge-pill badge-info text-white">Pending</span>
                            @elseif ($response->approval)
                                <span class="badge badge-pill badge-success">Approved</span>
                            @else
                                <span class="badge badge-pill badge-danger">Denied</span>
                            @endif
                        </td>
                        <td class="text-center py-2">
                            <a href="{{ route('requirement.view', [$response->requirement->id]) }}" class="btn btn-sm btn-primary">
                                Preview
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">
                            No results...
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
