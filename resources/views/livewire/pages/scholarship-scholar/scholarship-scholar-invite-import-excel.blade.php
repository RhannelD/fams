<div>
    @livewire('add-ins.scholarship-program-livewire', [$scholarship_id], key('page-tabs-'.time().$scholarship_id))
    
    <hr class="mb-2">
	<div class="mx-auto mxw-1300px">
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info">
                    Import excel file to invite scholars.
                    <a data-toggle="collapse" href="#excel_example" role="button" aria-expanded="false" aria-controls="excel_example">
                        Click here to view exampple.
                    </a>
                </div>
                <div class="collapse my-2" id="excel_example">
                    <div class="card card-body py-3">
                        <h4>Excel format example</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td>juandelacruz@email.com</td>
                                    <td>Category 1</td>
                                </tr>
                                <tr>
                                    <td>obie.oconner@email.com</td>
                                    <td>Category 2</td>
                                </tr>
                                <tr>
                                    <td>ronny.muller@email.com</td>
                                    <td>Category 1</td>
                                </tr>
                                <tr>
                                    <td colspan="2">...</td>
                                </tr>
                            </table>
                        </div>
                        <div class="alert alert-info mb-0">
                            <ul class="my-1">
                                <li>First collumn must be valid email.</li>
                                <li>Second collumn must be an exact match to scholarship's categories.</li>
                                <li>File extension must be '.xlsx' (Microsoft Excel Open XML Spreadsheet).</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="custom-file">
                    <input wire:model="excel" type="file" class="custom-file-input" id="customFile">
                    <label class="custom-file-label" for="customFile">
                        <i class="fas fa-spinner fa-spin" 
                            wire:loading 
                            wire:target="excel"
                            >
                        </i>
                        @if ( isset($excel) )
                            {{ $excel->getClientOriginalName() }}
                        @else
                            Choose excel file
                        @endif
                    </label>
                </div>
                @error('excel') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                @isset($dataset[0])
                    <h3>Valid Information</h3>
                    <table class="table table-sm table-bordered">
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                            <th>Category</th>
                            <th>Account</th>
                        </tr>
                        @foreach ($dataset as $row)
                            <tr>
                                <td>{{ $loop->index }}</td>
                                <td>{{ $row['email'] }}</td>
                                <td>{{ $row['category'] }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    </table>
                @endisset
            </div>
            <div class="col-12">
                @isset($dataset_invalid[0])
                    <hr class="my-2">
                    <h4 class="my-1">
                        <a data-toggle="collapse" href="#collapse_invalid" role="button" aria-expanded="false" aria-controls="collapse_invalid">
                            Invalid Information 
                            [{{ count($dataset_invalid) }}]
                        </a>
                    </h4>
                    <div class="collapse" id="collapse_invalid">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>#</th>
                                <th>Email</th>
                                <th>Category</th>
                            </tr>
                            @foreach ($dataset_invalid as $row)
                                <tr>
                                    <td rowspan="2">{{ $loop->index }}</td>
                                    <td>{{ $row['email'] }}</td>
                                    <td>{{ $row['category'] }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-danger">
                                        <ul class="my-0">
                                            @foreach ($row['error'] as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <hr class="mt-1 mb-5">
                @endisset
            </div>
        </div>
    </div>
</div>
