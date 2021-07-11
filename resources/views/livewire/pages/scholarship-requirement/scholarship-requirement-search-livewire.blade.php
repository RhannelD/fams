<div>
    <div class="d-flex justify-content-end my-1">
        {{ $requirements->links() }}
    </div> 

    <div class=" table-responsive">
        <table class="table table-borderless">
            <tbody>
                @forelse ($requirements as $requirement)
                    <tr>
                        <td>
                            <div class="shadow p-2 mx-2 bg-white rounded d-flex bd-highlight requirement-item
                                @if ($loop->last)
                                    mb-5 
                                @else
                                    mb-0 
                                @endif    
                                "
                                style="cursor: pointer;">
                                <div class="mr-auto p-2 bd-highlight">
                                    {{ $requirement->requirement }}
                                </div>
                                <div class="bd-highlight mx-1 my-auto">
                                    <h5>
                                        @if ($requirement->promote)
                                            <span class="badge badge-pill badge-secondary">Application</span>
                                        @else
                                            <span class="badge badge-pill badge-primary">Renewal</span>
                                        @endif
                                    </h5>
                                </div>
                                <div class="bd-highlight mx-1 my-auto">
                                    <h5>
                                        @php 
                                            $date_end = \Carbon\Carbon::parse($requirement->end_at);
                                            $date_now = \Carbon\Carbon::now()->toDateTimeString();
                                        @endphp
                                        @if ($date_end > $date_now)
                                            <span class="badge badge-pill badge-success">Ongoing</span>
                                        @else
                                            <span class="badge badge-pill badge-danger">Closed</span>
                                        @endif
                                    </h5>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td>
                            <div class="shadow p-2 my-3 mb-5 bg-white rounded d-flex bd-highlight">
                                <div class="mr-auto p-2 bd-highlight">
                                    No results...
                                </div>
                            </div>
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
