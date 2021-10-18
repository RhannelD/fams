<div>
    <div class="d-flex justify-content-end my-1">
        {{ $requirements->links() }}
    </div> 

    <div class="table-responsive">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>#</th>
                    <th>Requirement</th>
                    <th class="text-right px-1">Responds</th>
                    <th class="px-1">Category</th>
                    <th class="text-center px-1">Target</th>
                    <th class="text-center px-1">State</th>
                    <th class="text-center px-1">Actions</th>
                </tr>
                @forelse ($requirements as $requirement)
                    <tr>
                        <th>
                            {{ ( ($loop->index + 1) + ( ($show_row * $page ) - $show_row) ) }}
                        </th>
                        <td>
                            {{ $requirement->requirement }}
                        </td>
                        <td class="text-right pr-2 px-1">
                            <strong>
                                {{ $requirement->responses->whereNotNull('submit_at')->count() }}
                            </strong>
                            @if ( !$requirement->promote && $requirement->categories->count() > 0 )
                                /{{ $requirement->categories->first()->category->scholars->count() }}
                            @endif
                        </td>
                        <td class="px-1">
                            @if ( $requirement->categories->count() > 0 )
                                {{ $requirement->categories->first()->category->category }}
                            @endif
                        </td>
                        <td class="text-center px-1">
                            @if ($requirement->promote)
                                <span class="badge badge-pill badge-secondary">Application</span>
                            @else
                                <span class="badge badge-pill badge-primary">Renewal</span>
                            @endif
                        </td>
                        <td class="text-center px-1">
                            @switch( $requirement->can_be_accessed() )
                                @case('ongoing')
                                    <span class="badge badge-pill badge-success">Ongoing</span>
                                    @break

                                @case('disabled')
                                    <span class="badge badge-pill badge-dark">Disabled</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="text-center d-flex px-1 py-2">
                            <a href="{{ route('scholarship.requirement.open', [$requirement->id]) }}" class="btn btn-sm btn-success ml-auto">
                                Preview
                            </a>
                            <a href="{{ route('scholarship.requirement.responses', [$requirement->id]) }}" class="btn btn-sm btn-primary ml-1 mr-auto">
                                Responses
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">
                            No results...
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        $(".requirement-item").hover(function () {
            $(this).toggleClass("shadow-lg bg-light");
        });
    </script>
</div>
