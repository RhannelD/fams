<div>
    <div class="row mt-4">
        <div class="col-12">
            <h4>Invites</h4>
            <hr class="my-2">
            <div class="row">
                @forelse ($invites as $invite)
                    <div class="col-md-6 col-xl-4">
                        <div class="card my-2 border-dark">
                            <div class="card-header py-1 bg-white">
                                <h4 class="my-2">
                                    <strong>
                                        {{ $invite->category->scholarship->scholarship }}
                                    </strong>
                                </h4>
                            </div>
                            <div class="card-body py-2">
                                <table>
                                    <tr>
                                        <td>
                                            Category:
                                        </td>
                                        <td>
                                            {{ $invite->category->category }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Amount:
                                        </td>
                                        <td>
                                            Php {{ $invite->category->amount }}
                                        </td>
                                    </tr>
                                    @isset($invite->respond)
                                        <tr>
                                            <td>
                                                Respond:
                                            </td>
                                            <td>
                                                @if ($invite->respond)
                                                    <span class="badge badge-success">
                                                        Approved
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger">
                                                        Denied
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endisset
                                </table>
                            </div>
                            @if ( is_null($invite->respond) )
                                <div class="card-footer d-flex justify-content-end py-1 bg-white">
                                    <button wire:click="approve({{ $invite->id }})" class="btn btn-success mx-1">
                                        Approve
                                    </button>
                                    <button wire:click="deny_confirm({{ $invite->id }})" class="btn btn-danger">
                                        Deny
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            No Results
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('swal:confirm:deny_invite', event => { 
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
