<div>
    <div class="d-flex justify-content-end my-1">
        {{ $officers->links() }}
    </div> 

    <div class="table-wrap table-responsive">
        <table class="table myaccordion" id="accordions">
            <thead>
                <tr>
                    @if (Auth::user()->usertype != 'scholar')
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th></th>
                    @else
                        <th>Name</th>
                        <th>Email</th>
                        <th></th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($officers as $officer)
                    <tr class="align-middle text-nowrap">
                        @if (Auth::user()->usertype != 'scholar')    
                            <th>
                                {{ ( ($loop->index + 1) + ( ($show_row * $page ) - $show_row) ) }}
                            </th>
                            <td>
                                {{ $officer->fmlname() }}
                            </td>
                            <td>
                                {{ $officer->email }}
                            </td>
                            <td>
                                {{ $officer->phone }}
                            </td>
                            <td class="py-2 text-center">
                                <button class="btn btn-info btn-sm text-white" type="button" 
                                    data-toggle="collapse" 
                                    data-target="#collapse{{ $officer->id }}" 
                                    aria-expanded="true" 
                                    aria-controls="collapse{{ $officer->id }}"
                                    >
                                    <i class="fas fa-info-circle"></i>
                                </button>
                                @if ( $officer->id != Auth::id() )
                                    <a href="{{ route('user.chat', ['rid'=>$officer->id]) }}" class="btn btn-info btn-sm text-white">
                                        <i class="fas fa-comments"></i>
                                    </a>
                                @endif
                            </td>
                        @else
                            <td>
                                {{ $officer->fmlname() }}
                            </td>
                            <td>
                                {{ $officer->email }}
                            </td>
                            <td class="py-2 text-center">
                                <a href="{{ route('user.chat', ['rid'=>$officer->id]) }}" class="btn btn-info btn-sm text-white">
                                    <i class="fas fa-comments"></i>
                                </a>
                            </td>
                        @endif
                    </tr>
                    @if (Auth::user()->usertype != 'scholar')
                        @include('livewire.pages.scholarship-officer.scholarship-officer-search-info-livewire')
                    @endif
                @empty
                    <tr>
                        <td colspan="6">No results...</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>