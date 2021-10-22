<div>
    @livewire('add-ins.scholarship-program-livewire', [$scholarship_id], key('page-tabs-'.time().$scholarship_id))
    
    <ul wire:ignore class="nav nav-tabs d-flex justify-content-end my-2" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a id="send-sms-tab" data-toggle="tab" href="#send-sms" role="tab" aria-controls="send-sms"
                wire:click="$set('tab', '')"
                class="nav-link {{ $tab==''?'active':'' }}"
                aria-selected="{{ $tab==''?'true':'' }}"
                >
                Send SMS
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a id="smses-tab" data-toggle="tab" href="#smses" role="tab" aria-controls="smses"
                wire:click="$set('tab', 'SMSes')"
                class="nav-link {{ $tab=='SMSes'?'active':'' }}"
                aria-selected="{{ $tab=='SMSes'?'false':'' }}"
                >
                SMSes
            </a>
        </li>
    </ul>
    <div class="tab-content mt-3" id="pills-tabContent">
        <div wire:ignore.self class="tab-pane fade {{ $tab==''?'show active':'' }}" id="send-sms" role="tabpanel" aria-labelledby="send-sms-tab">
            @include('livewire.pages.send.send-sms-sending-livewire')
        </div>
        <div wire:ignore class="tab-pane fade {{ $tab=='SMSes'?'show active':'' }}" id="smses" role="tabpanel" aria-labelledby="smses-tab">
            @livewire('send.send-sms-list-livewire', [$scholarship_id])

            @livewire('send.send-sms-view-livewire')
        </div>
    </div>

    <script>
        window.addEventListener('smses-tab', event => {
            $('#smses-tab').tab('show');
        });
    </script>
</div>
