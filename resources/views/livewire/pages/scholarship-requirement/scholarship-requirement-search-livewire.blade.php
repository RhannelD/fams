<div>
    <div class="d-flex justify-content-end my-1">
        {{ $requirements->links() }}
    </div> 

    <div class=" table-responsive">
        <table class="table table-borderless table-hover">
            <tbody>
                @forelse ($requirements as $requirement)
                    <tr>
                        <td>
                            <div class="shadow p-2 mb-0 bg-white rounded d-flex bd-highlight">
                                <div class="mr-auto p-2 bd-highlight">
                                    {{ $requirement->requirement }}
                                </div>
                                <button class="btn btn-info bd-highlight mx-1 text-white">
                                    Open
                                </button>
                                <button class="btn btn-info bd-highlight mx-1 text-white">
                                    Edit
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td>
                            <div class="shadow p-2 mb-0 bg-white rounded d-flex bd-highlight">
                                <div class="mr-auto p-2 bd-highlight">
                                    No Results
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
