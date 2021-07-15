<div>
    <div class="d-flex justify-content-end my-1">
        {{ $scholars->links() }}
    </div> 

    <div class="table-wrap table-responsive">
        <table class="table myaccordion table-hover" id="accordion">
            <thead>
                <tr>
                    @if (!Auth::user()->usertype == 'scholar')
                        <th>ID</th>
                        <th>Name</th>
                        @empty($category_id)
                            <th>Category</th>
                        @endempty
                        <th>Email</th>
                        <th>Phone</th>
                        <th></th>
                    @else  
                        <th>Name</th>
                        <th>Email</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($scholars as $scholar)
                    <tr 
                        @if (!Auth::user()->usertype == 'scholar')    
                            data-toggle="collapse" 
                            data-target="#collapse{{ $scholar->user_id }}" 
                            aria-expanded="true" 
                            aria-controls="collapse{{ $scholar->user_id }}" 
                            aria-expanded="false"
                        @endif
                        >
                        @if (!Auth::user()->usertype == 'scholar')
                            <th scope="row">{{ $scholar->user_id }}</th>
                            <td>{{ $scholar->firstname }} {{ $scholar->middlename }} {{ $scholar->lastname }}</td>
                            @empty($category_id)
                                <td>{{ $scholar->category }}</td>
                            @endempty
                            <td>{{ $scholar->email }}</td>
                            <td>{{ $scholar->phone }}</td>
                            <td>
                                <i class="fa" aria-hidden="true"></i>
                            </td>
                        @else  
                            <td>{{ $scholar->firstname }} {{ $scholar->middlename }} {{ $scholar->lastname }}</td>
                            <td>{{ $scholar->email }}</td>
                        @endif
                    </tr>
                    @if (!Auth::user()->usertype == 'scholar')
                        <tr>
                            <td colspan="6" id="collapse{{ $scholar->user_id }}" data-parent="#accordion" class="collapse acc p-1" >
                                <div class="card mb-3 shadow-sm ">
                                    <div class="card-body p-2">
                                        <div class="row">
                                            <div class="col-md-6 pb-0">
                                                <table class="table table-borderless table-sm m-0">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="2">
                                                                Scholar Info
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>ID:</td>
                                                            <td>{{ $scholar->user_id }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Full Name:</td>
                                                            <td>{{ $scholar->firstname }} {{ $scholar->middlename }} {{ $scholar->lastname }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Phonenumber:</td>
                                                            <td>{{ $scholar->phone }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Email:</td>
                                                            <td>{{ $scholar->email }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Gender:</td>
                                                            <td>{{ $scholar->gender }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Religion:</td>
                                                            <td>{{ $scholar->religion }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Birth Date:</td>
                                                            <td>{{ $scholar->birthday }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Birth Place:</td>
                                                            <td>{{ $scholar->birthplace }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-6 pb-0">
                                                <table class="table table-borderless table-sm m-0">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="2">
                                                                Scholarship
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Category:</td>
                                                            <td>{{ $scholar->category }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Amount:</td>
                                                            <td>{{ $scholar->amount }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Date Joined:</td>
                                                            <td>{{ date_format(new DateTime($scholar->created_at),"M d, Y") }}</td>
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