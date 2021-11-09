<div>
    @livewire('add-ins.scholarship-program-livewire', [$scholarship_id, 'scholars'], key('page-tabs-'.time().$scholarship_id))
    
	<div class="mx-auto mxw-1300px mt-2">
        <div class="row mx-1">
            <div class="col-12">
                <div class="alert alert-info">
                    Import excel file to invite scholars.
                    <a data-toggle="collapse" href="#excel_example" role="button" aria-expanded="false" aria-controls="excel_example">
                        Click here to view example.
                    </a>
                </div>
                <div class="collapse my-2" id="excel_example">
                    <div class="card card-body py-3">
                        <h4>Excel format example</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                @if ( $category_count>1 )
                                    <tr>
                                        <td>juandelacruz@g.batstate-u.edu.ph</td>
                                        <td>Category 1</td>
                                    </tr>
                                    <tr>
                                        <td>obie.oconner@g.batstate-u.edu.ph</td>
                                        <td>Category 2</td>
                                    </tr>
                                    <tr>
                                        <td>ronny.muller@g.batstate-u.edu.ph</td>
                                        <td>Category 1</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>juandelacruz@g.batstate-u.edu.ph</td>
                                    </tr>
                                    <tr>
                                        <td>obie.oconner@g.batstate-u.edu.ph</td>
                                    </tr>
                                    <tr>
                                        <td>ronny.muller@g.batstate-u.edu.ph</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="2">...</td>
                                </tr>
                            </table>
                        </div>
                        <div class="alert alert-info mb-0">
                            <ul class="my-1">
                                <li>First collumn must be valid email.</li>
                                @if ( $category_count>1 )
                                    <li>Second collumn must be an exact match to scholarship's categories.</li>
                                @endif
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
        <div class="row mt-2 mx-1">
            <div class="col-12">
                @isset($dataset[0])
                    <h3 class="my-auto">Valid Information [{{ count($dataset) }}]</h3>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-success my-1"
                            wire:click='confirm_invite_all' 
                            wire:loading.attr='disabled'
                            wire:target='invite_all'
                            >
                            <i class="fas fa-spinner fa-spin"
                                wire:loading 
                                wire:target='invite_all'
                                >
                            </i>
                            Invite All
                        </button>
                        <button class="btn btn-danger my-1 ml-1"
                            wire:click="confirm_cancel_all"
                            wire:loading.attr='disabled'
                            wire:target='cancel_all'
                            >
                            <i class="fas fa-spinner fa-spin"
                                wire:loading 
                                wire:target='cancel_all'
                                >
                            </i>
                            Cancel All
                        </button>
                        <button class="btn btn-info text-white my-1 ml-1"
                            wire:click="refreshing"
                            wire:loading.attr='disabled'
                            >
                            <i class="fas fa-spinner fa-spin"
                                wire:loading 
                                wire:target="refreshing"
                                >
                            </i>
                            Refresh
                        </button>
                    </div>
                    <table class="table table-sm table-bordered" wire:poll.8000ms>
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                            @if ( $category_count>1 )
                                <th>Category</th>
                            @endif
                            <th>Account</th>
                            <th>Invite</th>
                            <th></th>
                        </tr>
                        @foreach ($dataset as $row)
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $row['email'] }}</td>
                                @if ( $category_count>1 && isset($row['category']) )
                                    <td>{{ $row['category'] }}</td>
                                @endif
                                <td>
                                    @if ( isset($row['account']) )
                                        <a>
                                            {{ $row['account'] }}
                                        </a>
                                    @else
                                        <span class="badge badge-secondary">Not Yet Registered</span>
                                    @endif    
                                </td>
                                    @if ( isset($row['invite']) && $row['invite'] )
                                        @if ( !isset($row['sent']) )
                                            <td>
                                                <span class="badge badge-pill badge-info text-white">Sending...</span>
                                            </td>
                                        @elseif ( $row['sent'] )
                                            <td>
                                                <span class="badge badge-pill badge-success">Invited</span>
                                            </td>
                                        @else
                                            <td>
                                                <span class="badge badge-pill badge-danger">Email not sent!</span>
                                            </td>
                                            <td class="text-center py-1">
                                                <button class="btn btn-sm btn-success"
                                                    wire:click='resend_invite({{ $row['invite_id'] }})'
                                                    wire:loading.attr='disabled'
                                                    wire:target="resend_invite({{ $row['invite_id'] }})"
                                                    >
                                                    <i class="fas fa-spinner fa-spin" id="resend_invite_icon_{{ $row['invite_id'] }}"
                                                        wire:loading 
                                                        wire:target="resend_invite({{ $row['invite_id'] }})"
                                                        >
                                                    </i>
                                                    <span id="resend_invite_label_{{ $row['invite_id'] }}"
                                                        wire:loading.remove
                                                        wire:target="resend_invite({{ $row['invite_id'] }})"
                                                        >
                                                        Resend
                                                    </span>
                                                </button>
                                            </td>
                                        @endif
                                    @else
                                        <td>
                                            ...
                                        </td>
                                    @endif
                                </td>
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
                                @if ( $category_count>1 )
                                    <th>Category</th>
                                @endif
                            </tr>
                            @foreach ($dataset_invalid as $row)
                                <tr>
                                    <td rowspan="2">{{ $loop->index+1 }}</td>
                                    <td>{{ $row['email'] }}</td>
                                    @if ( $category_count>1  && isset($row['category']) )
                                        <td>{{ $row['category'] }}</td>
                                    @endif
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
    
    <script>

        window.addEventListener('swal:confirm:invite_all', event => { 
            swal({
                title: event.detail.message,
                text: event.detail.text,
                icon: event.detail.type,
                buttons: true,
            })
            .then((willConfirm) => {
                if (willConfirm) {
                    @this.call(event.detail.function)
                }
            });
        });

        window.addEventListener('swal:confirm:cancel_all', event => { 
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
