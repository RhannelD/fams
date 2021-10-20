<div class="flex-grow-1 d-flex flex-column">
@if ( isset($chat_user) )
    @include('livewire.pages.user-chat.user-chat-convo-livewire')
@else
    @include('livewire.pages.user-chat.user-chat-new-convo-livewire')
@endif
</div>
