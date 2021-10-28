<div>
    <div class="card my-2">
        <h4 class="card-header bg-dark text-white">Scholarships</h4>
        <div class="card-body">
            @forelse ($scholarships as $scholarship)
                @if (!$loop->first)
                    <hr>
                @endif
                <table>
                    <tbody>
                        <tr>
                            <td>Scholarship:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $scholarship->scholarship->scholarship }}</td>
                        </tr>
                        <tr>
                            <td>Position:</td>
                            <td class="pl-sm-1 pl-md-2">{{ $scholarship->position->position }}</td>
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
