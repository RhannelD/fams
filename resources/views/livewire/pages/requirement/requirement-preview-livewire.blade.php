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
    @livewire('add-ins.scholarship-program-livewire', [$requirement->scholarship_id], key('page-tabs-'.time().$requirement->scholarship_id))

    <div class="row mx-1 mt-2">
        <div class="col-12 col-md-3 mb-2">
            <div class="card shadow mb-2 requirement-item-hover ">
                <div class="card-body">

                    @if ( !isset($scholar_response) )
                        @if ($requirement->promote && $is_scholar)
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
                            @if ( $requirement->has_categories() )
                                @switch( $requirement->can_be_accessed() )
                                    @case('ongoing')
                                        <a href="{{ route('requirement.response', [$requirement->id]) }}" class="btn btn-success btn-block pr-md-4">
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
                            @else
                                <div class="alert alert-danger my-auto">
                                    You can't respond to this requirement.<br>
                                    Category has not been set.
                                </div>
                            @endif
                        @endif

                    @elseif ( $scholar_response->cant_be_edit() )
                        <div class="alert alert-info mb-2">
                            You can't edit your response anymore.
                        </div>
                        <a href="{{ route('requirement.response', [$requirement->id]) }}" class="btn btn-info btn-block text-white">
                            View your response
                        </a>

                    @elseif ( !$scholar_response->cant_be_edit() )
                        @switch( $requirement->can_be_accessed() )
                            @case('disabled')
                                <div class="alert alert-info mb-2">
                                    Due date is finished. You cant edit or submit your response. 
                                </div>
                                @break

                            @default
                                <a href="{{ route('requirement.response', [$requirement->id]) }}" class="btn btn-info btn-block text-white">
                                    Edit your response
                                </a>
                                @break
                        @endswitch
                        
                    @endif

                </div>
            </div>

            <hr>
        </div>

        <div class="col-12 col-md-9 order-md-first">
            <div class="card border-primary mb-4 shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="my-auto">
                        @isset( $requirement->requirement )
                            <strong>{{ $requirement->requirement }}</strong>
                        @endisset
                    </h2>
                </div>
                <div class="card-body border-primary">
                    <p class="mb-0">
                        @isset( $requirement->description )
                            {!! Purify::clean($requirement->description) !!}
                        @endisset
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 px-4 mb-2">

                    <h5><strong>Scholar Category</strong></h5>
                    @forelse ($requirement->categories as $category)
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
                    @empty
                        <hr class="my-1">
                        <div class="alert alert-info my-auto">
                            Category has not been set.
                        </div>
                    @endforelse

                </div>
                <div class="col-md-6 px-4 mb-2">

                    <h5><strong>Access</strong></h5>
                    <hr class="my-1">
                    <table>
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
                                    @case('ongoing')
                                        <span class="badge badge-pill badge-success">Ongoing</span>
                                        @break

                                    @case('disabled')
                                        <span class="badge badge-pill badge-dark">Disabled</span>
                                        @break
                                @endswitch

                            </td>
                        </tr>
                        @if ( is_null($requirement->enable) )
                            <tr>
                                <td>Start Date:</td>
                                <td class="pl-2">{{ date_format(new DateTime($requirement->start_at),"M d,  Y h:i A") }}</td>
                            </tr>
                            <tr>
                                <td>End Date:</td>
                                <td class="pl-2">{{ date_format(new DateTime($requirement->end_at),"M d, Y  h:i A") }}</td>
                            </tr>
                        @endif
                    </table>

                </div>
            </div>

            <hr class="mt-1">

            @isset( $scholar_response )
                @include('livewire.pages.requirement.requirement-response-view-livewire')
            @endisset

        </div>
    </div>

    @isset( $scholar_response )
        <script>
            window.addEventListener('swal:confirm:delete_response_{{ $scholar_response->id }}', event => { 
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
    @endisset
@endif
</div>
