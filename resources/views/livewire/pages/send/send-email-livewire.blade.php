<div>
    @livewire('add-ins.scholarship-program-livewire', [$scholarship_id, 'send-email'], key('page-tabs-'.time().$scholarship_id))
    
    <ul wire:ignore class="nav nav-tabs d-flex justify-content-end my-2 mx-1" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a id="send-email-tab" data-toggle="tab" href="#send-email" role="tab" aria-controls="send-email"
                wire:click="$set('tab', '')"
                class="nav-link {{ $tab==''?'active':'' }}"
                aria-selected="{{ $tab==''?'true':'' }}"
                >
                Send Email
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a id="emails-tab" data-toggle="tab" href="#emails" role="tab" aria-controls="emails"
                wire:click="$set('tab', 'emails')"
                class="nav-link {{ $tab=='emails'?'active':'' }}"
                aria-selected="{{ $tab=='emails'?'false':'' }}"
                >
                Emails
            </a>
        </li>
    </ul>
    <div class="tab-content mt-3 mx-3" id="pills-tabContent">
        <div wire:ignore.self class="tab-pane fade {{ $tab==''?'show active':'' }}" id="send-email" role="tabpanel" aria-labelledby="send-email-tab">
            @include('livewire.pages.send.send-email-sending-livewire')
        </div>
        <div wire:ignore class="tab-pane fade {{ $tab=='emails'?'show active':'' }}" id="emails" role="tabpanel" aria-labelledby="emails-tab">
            @livewire('send.send-email-list-livewire', [$scholarship_id])

            @livewire('send.send-email-view-livewire')
        </div>
    </div>

    <script>
        window.addEventListener('emails-tab', event => {
            $('#emails-tab').tab('show');
        });
    </script>
</div>
