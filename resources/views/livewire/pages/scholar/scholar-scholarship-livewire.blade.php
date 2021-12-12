<div>
    <div class="card my-2">
        <h4 class="card-header bg-dark text-white">Scholarships</h4>
        <div class="card-body">
            @forelse ($scholar_scholarships as $scholarship)
                @if (!$loop->first)
                    <hr>
                @endif
                <table>
                    <tbody>
                        <tr>
                            <td>Scholarship:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $scholarship->category->scholarship->scholarship }}</td>
                        </tr>
                        <tr>
                            <td>Category:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $scholarship->category->category }}</td>
                        </tr>
                        <tr>
                            <td>Amount:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $scholarship->category->amount }}</td>
                        </tr>
                        <tr>
                            <td>Year & Sem:</td>
                            <td class="pl-sm-1 pl-md-2">
                                {{ $scholarship->acad_year }}-{{ $scholarship->acad_year+1 }} 
                                {{ $scholarship->acad_sem=='1'? 'First': 'Second' }} Sem
                            </td>
                        </tr>
                        <tr>
                            <td>Accepted at:</td>
                            <td class="pl-sm-1 pl-md-2">{{ \Carbon\Carbon::parse($scholarship->created_at)->format("M d, Y h:i A") }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button wire:click="confirm_delete({{ $scholarship->id }})" class="btn btn-danger btn-sm">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            @empty
                <div class="alert alert-info my-auto">
                    None
                </div>
            @endforelse
            @if ( count($prev_scholar_scholarships) > 0 )
                <hr>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#prev_scholarships" aria-expanded="false" aria-controls="prev_scholarships">
                        See more
                    </button>
                </div>
                <div class="collapse" id="prev_scholarships">
                    @foreach ($prev_scholar_scholarships as $scholarship)
                            <hr>
                        <table>
                            <tbody>
                                <tr>
                                    <td>Scholarship:</td>
                                    <td class="pl-sm-1 pl-md-2">{{ $scholarship->category->scholarship->scholarship }}</td>
                                </tr>
                                <tr>
                                    <td>Category:</td>
                                    <td class="pl-sm-1 pl-md-2">{{ $scholarship->category->category }}</td>
                                </tr>
                                <tr>
                                    <td>Amount:</td>
                                    <td class="pl-sm-1 pl-md-2">{{ $scholarship->category->amount }}</td>
                                </tr>
                                <tr>
                                    <td>Year & Sem:</td>
                                    <td class="pl-sm-1 pl-md-2">
                                        {{ $scholarship->acad_year }}-{{ $scholarship->acad_year+1 }} 
                                        {{ $scholarship->acad_sem=='1'? 'First': 'Second' }} Sem
                                    </td>
                                </tr>
                                <tr>
                                    <td>Accepted at:</td>
                                    <td class="pl-sm-1 pl-md-2">{{ \Carbon\Carbon::parse($scholarship->created_at)->format("M d, Y h:i A") }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <button wire:click="confirm_delete({{ $scholarship->id }})" class="btn btn-danger btn-sm">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>

        window.addEventListener('swal:confirm:delete_scholarship', event => { 
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
