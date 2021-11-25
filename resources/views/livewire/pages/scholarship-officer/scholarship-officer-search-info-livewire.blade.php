<tr>
    <td colspan="6" class="p-0 border-0">
        <div wire:ignore.self id="collapse{{ $officer->id }}" data-parent="#accordions" class="collapse acc p-0" >
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
                                        <td>Sex:</td>
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
        </div>
    </td>
</tr>