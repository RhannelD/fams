<div>
    <h1>Requirement</h1>

    <div class=" table-responsive">
        <table class="table table-borderless">
            <tbody>
                @for ($i = 1; $i <= 5; $i++)
                    <tr>
                        <td>
                            <div class="shadow p-2 mb-0 bg-white rounded d-flex bd-highlight">
                                <div class="mr-auto p-2 bd-highlight">
                                    
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
                @endfor
            </tbody>
        </table>
    </div>

    {{-- <table>
        <tbody>
            <tr>
                <td class="shadow p-3 mb-3 bg-white rounded">
                    Regular shadow
                </td>
            </tr>
            <tr>
                <td class="shadow p-3 mb-3 bg-white rounded">
                    Regular shadow
                </td>
            </tr>
            <tr>
                <td class="shadow p-3 mb-3 bg-white rounded">
                    Regular shadow
                </td>
            </tr>
        </tbody>
    </table> --}}

    {{-- <div class="shadow p-3 mb-3 bg-white rounded">Regular shadow</div>
    <div class="shadow p-3 mb-3 bg-white rounded">Regular shadow</div> --}}
</div>
