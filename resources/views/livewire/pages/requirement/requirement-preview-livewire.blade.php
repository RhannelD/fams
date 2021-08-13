<div>
    <div class="row mt-1 p-1">
        <div class="card col-12 bg-secondary text-white border-secondary">
            <h2 class="m-2 row">
                <strong class="my-auto">
                    {{ $requirement->scholarship->scholarship }}
                </strong>
                
                <div class="mr-1 ml-auto">
                    <a class="btn btn-light"
                        href="{{ route('scholarship.program', [$requirement->scholarship_id, 'home']) }}">
                        <i class="fas fa-newspaper"></i>
                        <strong>Home</strong>
                    </a>
                    <a class="btn btn-light"
                        href="{{ route('scholarship.program', [$requirement->scholarship_id, 'scholar']) }}">
                        <i class="fas fa-user-graduate"></i>
                        <strong>Scholars</strong>
                    </a>
                    <a class="btn btn-light"
                        href="{{ route('scholarship.program', [$requirement->scholarship_id, 'officer']) }}">
                        <i class="fas fa-address-card"></i>
                        <strong>Officers</strong>
                    </a>
                    @if (Auth::user()->usertype != 'scholar')
                        <a class="btn btn-light"
                            href="{{ route('scholarship.program', [$requirement->scholarship_id, 'requirement']) }}">
                            <i class="fas fa-file-alt"></i>
                            <strong>Requirements</strong>
                        </a>
                    @endif
                </div>
            </h2>
        </div>
    </div>

    <hr>
    <div class="row">
        <div class="col-12 col-md-3 mb-2">

            <div class="card shadow mb-2 requirement-item-hover ">
                <div class="card-body">

                    <a href="{{ route('reponse', [$response->requirement_id]) }}" class="btn btn-success btn-block pr-md-4">
                        <i class="fas fa-paper-plane mr-1"></i>
                        Respond
                    </a>

                </div>
            </div>

            <hr>
        </div>

        <div class="col-12 col-md-9 order-md-first">
            <div class="card bg-primary border-primary mb-4 shadow">
                <div class="card-body text-white border-primary">
                    <h2>
                        @isset( $requirement->requirement )
                            <strong>{{ $requirement->requirement }}</strong>
                        @endisset
                    </h2>
                    <p class="mb-0">
                        @isset( $requirement->description )
                            {{ $requirement->description }}
                        @endisset
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 px-4 mb-2">

                    <h5><strong>Scholar Category</strong></h5>
                    @foreach ($categories as $category)
                        <hr class="my-1">
                        <table>
                            <tr>
                                <td>Category:</td>
                                <td class="pl-2">{{ $category->category }}</td>
                            </tr>
                            <tr>
                                <td>Amount:</td>
                                <td class="pl-2">{{ $category->amount }}</td>
                            </tr>
                        </table>
                    @endforeach

                </div>
                <div class="col-md-6 px-4 mb-2">

                    <h5><strong>Access</strong></h5>
                    <hr class="my-1">
                    <table>
                        <tr>
                            <td>Start Date:</td>
                            <td class="pl-2">{{ date_format(new DateTime($requirement->start_at),"M d,  Y h:i A") }}</td>
                        </tr>
                        <tr>
                            <td>End Date:</td>
                            <td class="pl-2">{{ date_format(new DateTime($requirement->end_at),"M d, Y  h:i A") }}</td>
                        </tr>
                        <tr>
                            <td>For:</td>
                            <td class="pl-2">
                                @if ( $requirement->promote )
                                    New Applicants
                                @else
                                    Renewal/Old Scholars
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Availability:</td>
                            <td class="pl-2">
                                @php 
                                    $date_end = \Carbon\Carbon::parse($requirement->end_at);
                                    $date_now = \Carbon\Carbon::now()->toDateTimeString();
                                @endphp
                                @if (!isset($requirement->enable))
                                    @if ($date_end > $date_now)
                                        <span class="badge badge-pill badge-success">Ongoing</span>
                                    @else
                                        <span class="badge badge-pill badge-dark">Disabled</span>
                                    @endif
                                @elseif (!$requirement->enable)
                                    <span class="badge badge-pill badge-dark">Disabled</span>
                                @elseif ($date_end > $date_now)
                                    <span class="badge badge-pill badge-success">Ongoing</span>
                                @else
                                    <span class="badge badge-pill badge-danger">Finished</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                </div>
            </div>

            <hr class="mt-1">

            @isset( $response )
                <div class="card mx-md-3 mb-5">
                    <div class="card-body bg-white pb-2">
                        <div class="d-flex">
                            <h5 class="ml-0 mr-auto my-auto btn-block">
                                <a href="{{ route('reponse', [$response->requirement_id]) }}">   
                                    <div class="input-group mb-1 item-hover">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-primary text-white border-primary">
                                                <i class="fas fa-file-invoice"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control bg-white border-primary rounded-right" value="View Your Response" readonly>
                                    </div>     
                                </a>
                            </h5>
                            
                            <div class="mr-0 ml-2">
                                <button class="btn btn-danger">
                                    <i class="fas fa-minus-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <table>
                            <tr>
                                <td>Submitted at:</td>
                                <td class="pl-2">
                                    @if ( is_null($response->submit_at) )
                                        Not Yet Submitted
                                    @else
                                        {{ date_format(new DateTime($response->submit_at),"M d,  Y h:i A") }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Approval:</td>
                                <td class="pl-2">
                                    @if ( is_null($response->approval) )
                                        Not Yet Submitted
                                    @elseif ($response->approval)
                                        Approved
                                    @else
                                        Denied
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-footer bg-white">
                        <h5>Comments</h5>
                        <hr class="my-2">
                    </div>
                    <div class="card-footer bg-white">
                        
                        <label class="mb-1 pl-2">
                            <strong>
                                {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                            </strong>
                        </label>
                        <form class="form-group my-0" wire:submit.prevent="comment">
                            <div class="d-flex">
                                <textarea class="form-control rounded" rows="1" placeholder="Comment something..."></textarea>
                                <div>
                                    <button class="btn btn-dark rounded ml-2" type="submit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </div>
                            @error('comment.comment') <span class="text-danger">{{ $message }}</span> @enderror
                        </form>
                        
                    </div>
                </div>
            @endisset

        </div>
    </div>
</div>
