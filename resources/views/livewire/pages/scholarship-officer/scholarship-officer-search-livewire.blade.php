<div>
    <div class="d-flex justify-content-end my-1">
        {{ $officers->links() }}
    </div> 

    <div class="table-wrap table-responsive">
        <table class="table myaccordion table-hover" id="accordion">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($officers as $officer)
                    <tr data-toggle="collapse" data-target="#collapse{{ $officer->user_id }}" aria-expanded="true" aria-controls="collapse{{ $officer->user_id }}" aria-expanded="false">
                        <th scope="row">{{ $officer->user_id }}</th>
                        <td>{{ $officer->firstname }} {{ $officer->middlename }} {{ $officer->lastname }}</td>
                        <td>{{ $officer->position }}</td>
                        <td>{{ $officer->email }}</td>
                        <td>{{ $officer->phone }}</td>
                        <td>
                            <i class="fa" aria-hidden="true"></i>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" id="collapse{{ $officer->user_id }}" data-parent="#accordion" class="collapse acc p-1" >
                            <div class="card mb-3">
                                <div class="card-body p-2">
                                    <div class="row">
                                        <div class="col-md-6 pb-0">
                                            <table class="table table-borderless table-sm m-0">
                                                <tbody>
                                                    <tr>
                                                        <td>ID:</td>
                                                        <td>{{ $officer->user_id }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Full Name:</td>
                                                        <td>{{ $officer->firstname }} {{ $officer->middlename }} {{ $officer->lastname }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Phonenumber:</td>
                                                        <td>{{ $officer->phone }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Email:</td>
                                                        <td>{{ $officer->email }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6 pb-0">
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
                                                        <td>{{ $officer->birthday }}</td>
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
                @endforeach
            </tbody>
        </table>
    </div>
</div>