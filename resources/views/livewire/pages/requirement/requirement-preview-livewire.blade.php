<div>
@if ( is_null($requirement) )
    <div class="alert alert-danger mt-5 mb-2 mx-md-5">
        This requirement doesn't exist.
    </div>
    <div class="alert alert-info mx-md-5">
        This requirement might be deleted by the officers.
    </div>

@elseif ( !$requirement->promote && !$is_scholar )
    <div class="alert alert-info mt-5 mx-md-5">
        This requirement is unavailable on your scholarship program.
    </div>

@else
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

                    @if ( !isset($response) )
                        @if (!$access && $requirement->promote && $is_scholar)
                            <div class="alert alert-info mb-2">
                                You can't respond to this requirement.
                                <br>
                                Already a scholar of this scholarship program.
                            </div>

                        @elseif (!$access && !$requirement->promote && $is_scholar)
                            <div class="alert alert-info mb-2">
                                You can't respond to this requirement.
                                <br>
                                This is out of your scholarship category.
                            </div>

                        @elseif (!$access && !$requirement->promote && !$is_scholar)
                            <div class="alert alert-info mb-2">
                                You can't respond to this requirement.
                                <br>
                                This is out of your scholarship program.
                            </div>

                        @else
                            @switch( $requirement->can_be_accessed() )
                                @case('finished')
                                    <div class="alert alert-info mb-2">
                                        Due date is finished but you can still send a response.
                                    </div>
                                @case('ongoing')
                                    <a href="{{ route('reponse', [$requirement->id]) }}" class="btn btn-success btn-block pr-md-4">
                                        <i class="fas fa-paper-plane mr-1"></i>
                                        Respond
                                    </a>
                                    @break

                                @default
                                    <div class="alert alert-danger my-auto">
                                        You can't respond to this requirement.
                                    </div>
                                    @break
                            @endswitch
                            
                        @endif

                    @elseif ( $response->cant_be_edit() )
                        <div class="alert alert-info mb-2">
                            You can't edit your response anymore.
                        </div>
                        <a href="{{ route('reponse', [$requirement->id]) }}" class="btn btn-info btn-block text-white">
                            View your response
                        </a>

                    @elseif ( !$response->cant_be_edit() )
                        <a href="{{ route('reponse', [$requirement->id]) }}" class="btn btn-info btn-block text-white">
                            Edit your response
                        </a>
                        
                    @endif

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
                    @foreach ($requirement->categories as $category)
                        <hr class="my-1">
                        <table>
                            <tr>
                                <td>Category:</td>
                                <td class="pl-2">{{ $category->category->category }}</td>
                            </tr>
                            <tr>
                                <td>Amount:</td>
                                <td class="pl-2">{{ $category->category->amount }}</td>
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

                                @switch( $requirement->can_be_accessed() )
                                    @case('finished')
                                        <span class="badge badge-pill badge-danger">Finished</span>
                                        @break

                                    @case('ongoing')
                                        <span class="badge badge-pill badge-success">Ongoing</span>
                                        @break

                                    @case('disabled')
                                        <span class="badge badge-pill badge-dark">Disabled</span>
                                        @break
                                @endswitch

                            </td>
                        </tr>
                    </table>

                </div>
            </div>

            <hr class="mt-1">

            @isset( $response )
                @include('livewire.pages.requirement.requirement-response-view-livewire')
            @endisset

        </div>
    </div>

    
    <script>
        window.addEventListener('swal:confirm:delete_response_{{ $response_id }}', event => { 
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
@endif
</div>
