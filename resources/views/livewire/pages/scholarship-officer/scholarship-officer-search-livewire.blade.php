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
                        <th>Position</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th></th>
                    @else
                        <th>Name</th>
                        <th>Position</th>
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
                                {{ $officer->scholarship_officer->position->position }}
                            </td>
                            <td>
                                {{ $officer->email }}
                            </td>
                            <td>
                                {{ $officer->phone }}
                            </td>
                            <td class="py-2">
                                <button class="btn btn-info btn-sm text-white" type="button" 
                                    data-toggle="collapse" 
                                    data-target="#collapse{{ $officer->id }}" 
                                    aria-expanded="true" 
                                    aria-controls="collapse{{ $officer->id }}"
                                    >
                                    <i class="fas fa-info-circle"></i>
                                </button>
                                <a href="{{ route('user.chat', ['rid'=>$officer->id]) }}" class="btn btn-info btn-sm text-white">
                                    <i class="fas fa-comments"></i>
                                </a>
                            </td>
                        @else
                            <td>
                                {{ $officer->fmlname() }}
                            </td>
                            <td>
                                {{ $officer->scholarship_officer->position->position }}
                            </td>
                            <td>
                                {{ $officer->email }}
                            </td>
                            <th>
                                <a href="{{ route('user.chat', ['rid'=>$officer->id]) }}" class="btn btn-info btn-sm text-white">
                                    <i class="fas fa-comments"></i>
                                </a>
                            </th>
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

    <script>
        window.addEventListener('swal:confirm:remove_officer', event => { 
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