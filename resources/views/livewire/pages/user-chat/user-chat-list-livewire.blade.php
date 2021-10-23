<div class="list-group list-group-flush"> 
    <a class="list-group-item list-group-item-action bg-light tabs border-top mb-2"
        wire:click='set_receiver(null)'
        >
        <i class="fas fa-comment-medical"></i>
        New Message
    </a>
    <strong class="ml-3">
        Messages
    </strong>
    @forelse ($convos as $user)
        <button class="list-group-item list-group-item-action bg-light tabs border-right-0"
            wire:click='set_receiver({{ $user->id }})'
            >
            @if ( $rid == $user->id )
                <strong class="text-primary">
                    {{ $user->flname() }}  
                </strong>
            @else
                {{ $user->flname() }}  
            @endif
            @if ( $user->unseen_chats )
                <span class="badge badge-danger">
                    {{ $user->unseen_chats }}
                </span>
            @endif
        </button>
    @empty
        None
    @endforelse
    @if ( $convo_number>$convo_count )
        <button wire:click='load_more' class="btn btn-primary rounded-0">
            More
            <i wire:loading.remove wire:target='load_more' class="fas fa-chevron-circle-down"></i>
            <i wire:loading wire:target='load_more' class="fas fa-circle-notch fa-spin"></i>
        </button>
    @endif
</div>
