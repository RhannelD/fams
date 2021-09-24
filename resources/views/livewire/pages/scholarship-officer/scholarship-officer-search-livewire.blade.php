<div>
    <div class="d-flex justify-content-end my-1">
        {{ $officers->links() }}
    </div> 

    <div class="table-wrap table-responsive">
        <table class="table myaccordion table-hover" id="accordions">
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
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($officers as $officer)
                    <tr 
                        @if (Auth::user()->usertype != 'scholar')    
                            data-toggle="collapse" 
                            data-target="#collapse{{ $officer->id }}" 
                            aria-expanded="true" 
                            aria-controls="collapse{{ $officer->id }}" 
                            aria-expanded="false"
                        @endif
                        >
                        @if (Auth::user()->usertype != 'scholar')    
                            <th>
                                {{ ( ($loop->index + 1) + ( ($show_row * $page ) - $show_row) ) }}
                            </th>
                            <td>
                                {{ $officer->fmlname() }}
                            </td>
                            <td>
                                {{ $officer->scholarship_officers->where('scholarship_id', $scholarship_id)->first()->position->position }}
                            </td>
                            <td>
                                {{ $officer->email }}
                            </td>
                            <td>
                                {{ $officer->phone }}
                            </td>
                            <td>
                                <i class="fa" aria-hidden="true"></i>
                            </td>
                        @else
                            <td>
                                {{ $officer->fmlname() }}
                            </td>
                            <td>
                                {{ $officer->scholarship_officers->where('scholarship_id', $scholarship_id)->first()->position->position }}
                            </td>
                            <td>
                                {{ $officer->email }}
                            </td>
                        @endif
                    </tr>
                    @if (Auth::user()->usertype != 'scholar')
                        <tr>
                            <td colspan="6" id="collapse{{ $officer->id }}" data-parent="#accordions" class="collapse acc p-1" >
                                <div class="card mb-3 shadow-sm">
                                    <div class="card-body p-2">
                                        <div class="row">
                                            <div class="col-auto pb-0">
                                                <table class="table table-borderless table-sm m-0">
                                                    <tbody>
                                                        <tr>
                                                            <td>ID:</td>
                                                            <td>{{ $officer->id }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Full Name:</td>
                                                            <td>{{ $officer->fmlname() }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Phonenumber:</td>
                                                            <td>{{ $officer->phone }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Email:</td>
                                                            <td>{{ $officer->email }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Address:</td>
                                                            <td>{{ $officer->address }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-auto pb-0">
                                                <table class="table table-borderless table-sm m-0">
                                                    <tbody>
                                                        <tr>
                                                            <td>Gender:</td>
                                                            <td>{{ $officer->gender }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Religion:</td>
                                                            <td>{{ $officer->religion }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Birth Date:</td>
                                                            <td>{{ date_format(new DateTime($officer->birthday),"M d, Y") }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Birth Place:</td>
                                                            <td>{{ $officer->birthplace }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
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