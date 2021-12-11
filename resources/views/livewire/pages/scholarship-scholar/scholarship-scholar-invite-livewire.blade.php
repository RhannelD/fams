<div class="modal-content">
    <div class="modal-header bg-dark text-white">
    <h5 class="modal-title" id="exampleModalCenterTitle">Scholar Invite</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fas fa-times-circle text-white"></i></span>
        </button>
    </div>
    <div class="modal-body pt-1" style="min-height: 300px;"  wire:poll.8000ms>
        <div class="row"> 
            <div class="col-12 pt-0">
                <ul wire:ignore class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="send-tab" data-toggle="tab" href="#send" role="tab" aria-controls="send" aria-selected="true">
                            Send invites
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="false">
                            Pending invites
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="accepted-tab" data-toggle="tab" href="#accepted" role="tab" aria-controls="accepted" aria-selected="false">
                            Accepted invites
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab" aria-controls="rejected" aria-selected="false">
                            Rejected invites
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div wire:ignore.self class="tab-pane fade show active pt-1" id="send" role="tabpanel" aria-labelledby="send-tab">
                        @include('livewire.pages.scholarship-scholar.scholarship-scholar-invite-tab-send')
                    </div>
                    <div wire:ignore.self class="tab-pane fade pt-1" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                        @include('livewire.pages.scholarship-scholar.scholarship-scholar-invite-tab-pending')
                    </div>
                    <div wire:ignore.self class="tab-pane fade pt-1" id="accepted" role="tabpanel" aria-labelledby="accepted-tab">
                        @include('livewire.pages.scholarship-scholar.scholarship-scholar-invite-tab-accepted')
                    </div>
                    <div wire:ignore.self class="tab-pane fade pt-1" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                        @include('livewire.pages.scholarship-scholar.scholarship-scholar-invite-tab-rejected')
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>

        window.addEventListener('swal:confirm:delete_something', event => { 
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
