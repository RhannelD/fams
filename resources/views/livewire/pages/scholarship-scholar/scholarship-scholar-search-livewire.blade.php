<div>
    <div class="d-flex justify-content-end my-1 flex-wrap">
        <h3 class="my-auto mr-auto">
            {{ $acad_sem=='1'? 'First': 'Second' }} Semester of {{ $acad_year }}-{{ $acad_year+1 }} [{{ $num_of_scholars }}]
        </h3>
        {{ $scholars->links() }}
    </div> 

    <div class="table-wrap table-responsive">
        <table class="table table-sm" id="accordion">
            <thead>
                <tr>
                    @if ( !(Auth::user()->usertype == 'scholar') )
                        <th>#</th>
                        <th>Name</th>
                        @if ( empty($category_id) && count($categories)>1 )
                            <th>Category</th>
                        @endif
                        <th>Email</th>
                        <th>Phone</th>
                        <th></th>
                    @else  
                        <th>Name</th>
                        @if ( empty($category_id) && count($categories)>1 )
                            <th>Category</th>
                        @endif
                        <th>Email</th>
                        <th></th>
                    @endif
                </tr>
            </thead>
            <tbody class="accordion">
                @forelse ($scholars as $scholar)
                    <tr class="align-middle text-nowrap">
                        @if (Auth::user()->usertype != 'scholar')
                            <th scope="row">
                                {{ ( ($loop->index + 1) + ( ($show_row * $page ) - $show_row) ) }}
                            </th>
                            <td>
                                {{ $scholar->user->fmlname() }}
                            </td>
                            @if ( empty($category_id) && count($categories)>1 )
                                <td>
                                    {{ $scholar->category->category }}
                                </td>
                            @endif
                            <td>
                                {{ $scholar->user->email }}
                            </td>
                            <td>
                                {{ $scholar->user->phone }}
                            </td>
                            <td class="py-1 text-center">
                                <button class="btn btn-info btn-sm text-white" type="button" 
                                    data-toggle="collapse" 
                                    data-target="#collapse{{ $scholar->user_id }}" 
                                    aria-expanded="true" 
                                    aria-controls="collapse{{ $scholar->user_id }}"
                                    >
                                    <i class="fas fa-info-circle"></i>
                                </button>
                                <a href="{{ route('user.chat', ['rid'=>$scholar->user_id]) }}" class="btn btn-info btn-sm text-white">
                                    <i class="fas fa-comments"></i>
                                </a>
                                <a href="{{ route('scholarship.send.sms', ['scholarship_id' => $scholarship_id, 'rid'=>$scholar->user_id]) }}" 
                                    class="btn btn-info btn-sm text-white">
                                    <i class="fas fa-sms"></i>
                                </a>
                                <a href="{{ route('scholarship.send.email', ['scholarship_id' => $scholarship_id, 'rid'=>$scholar->user_id]) }}" 
                                    class="btn btn-info btn-sm text-white">
                                    <i class="fas fa-envelope"></i>
                                </a>
                                @isset($scholar->user->facebook)
                                    <a href="{{ $scholar->user->facebook->facebook_link }}" target="blank" class="btn btn-sm btn-primary">
                                        <i class="fab fa-facebook m-0"></i>
                                    </a>
                                @endisset
                                <button wire:click="remove_scholar_confirm({{ $scholar->id }})" class="btn btn-danger btn-sm">
                                    <i class="fas fa-user-times"></i>
                                </button>
                            </td>
                        @else  
                            <td>
                                {{ $scholar->user->fmlname() }}
                            </td>
                            @if ( empty($category_id) && count($categories)>1 )
                                <td>
                                    {{ $scholar->category->category }}
                                </td>
                            @endif
                            <td>
                                {{ $scholar->user->email }}
                            </td>
                            <td class="py-2 text-center">
                                <a href="{{ route('user.chat', ['rid'=>$scholar->user_id]) }}" class="btn btn-info btn-sm text-white">
                                    <i class="fas fa-comments"></i>
                                </a>
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
