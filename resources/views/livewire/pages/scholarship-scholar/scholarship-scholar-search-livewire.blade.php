<div>
    <div class="d-flex justify-content-end my-1">
        {{ $scholars->links() }}
    </div> 

    <div class="table-wrap table-responsive">
        <table class="table" id="accordion">
            <thead>
                <tr>
                    @if ( !(Auth::user()->usertype == 'scholar') )
                        <th>#</th>
                        <th>Name</th>
                        @empty($category_id)
                            <th>Category</th>
                        @endempty
                        <th>Email</th>
                        <th>Phone</th>
                        <th></th>
                    @else  
                        <th>Name</th>
                        @empty($category_id)
                            <th>Category</th>
                        @endempty
                        <th>Email</th>
                    @endif
                </tr>
            </thead>
            <tbody class="accordion">
                @forelse ($scholars as $scholar)
                    <tr>
                        @if (Auth::user()->usertype != 'scholar')
                            <th scope="row">
                                {{ ( ($loop->index + 1) + ( ($show_row * $page ) - $show_row) ) }}
                            </th>
                            <td>
                                {{ $scholar->user->fmlname() }}
                            </td>
                            @empty($category_id)
                                <td>
                                    {{ $scholar->category->category }}
                                </td>
                            @endempty
                            <td>
                                {{ $scholar->user->email }}
                            </td>
                            <td>
                                {{ $scholar->user->phone }}
                            </td>
                            <td class="py-2">
                                <button class="btn btn-info btn-sm text-white" type="button" 
                                    data-toggle="collapse" 
                                    data-target="#collapse{{ $scholar->user_id }}" 
                                    aria-expanded="true" 
                                    aria-controls="collapse{{ $scholar->user_id }}"
                                    >
                                    <i class="fas fa-info-circle"></i>
                                </button>
                                <a href="{{ $scholar->user->facebook->facebook_link }}" class="btn btn-sm btn-primary">
                                    <i class="fab fa-facebook m-0"></i>
                                </a>
                                <button wire:click="remove_scholar_confirm({{ $scholar->id }})" class="btn btn-danger btn-sm">
                                    <i class="fas fa-user-times"></i>
                                </button>
                            </td>
                        @else  
                            <td>
                                {{ $scholar->user->fmlname() }}
                            </td>
                            @empty($category_id)
                                <td>
                                    {{ $scholar->category->category }}
                                </td>
                            @endempty
                            <td>
                                {{ $scholar->user->email }}
                            </td>
                        @endif
                    </tr>
                    @if (Auth::user()->usertype != 'scholar')
                        @include('livewire.pages.scholarship-scholar.scholarship-scholar-search-info-livewire')
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
