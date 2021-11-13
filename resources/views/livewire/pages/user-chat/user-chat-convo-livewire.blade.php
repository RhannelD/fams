<nav class="navbar navbar-dark bg-primary">
    <a class="navbar-brand">
        <strong>
            {{ $chat_user->flname() }}
        </strong>
    </a>
</nav>
<div class="container-fluid flex-grow-1 d-flex flex-column position-relative" wire:poll.8000ms>
    <div class="row flex-grow-1">
        <div class="col-12 d-flex align-items-end">
            <div class="w-100">
                @if ( $messages_count > $chat_count )
                    <div class="w-100 d-flex justify-content-center">
                        <button wire:click='load_more' class="btn btn-primary mb-3 mt-1">
                            Load more
                            <i wire:loading.remove wire:target='load_more' class="fas fa-chevron-circle-up"></i>
                            <i wire:loading wire:target='load_more' class="fas fa-circle-notch fa-spin"></i>
                        </button>
                    </div>
                @endif
                @php
                    $user_id_icon = 0;
                @endphp
                @forelse ($messages as $message)
                    <div class="w-100 my-1 d-flex {{ $message->sender_id == Auth::id()? 'justify-content-end':'' }}">
                        @if ( $message->sender_id != Auth::id() && $user_id_icon != $message->sender_id )
                            <div class="border border-dark rounded-circle chat-head mr-1 d-flex justify-content-center" >
                                <h4 class="my-auto">
                                    <strong>
                                        {{ strtolower($message->sender->firstname[0]) }}
                                    </strong>
                                </h4>
                            </div>
                            @php
                                $user_id_icon = $message->sender_id;
                            @endphp
                        @else
                            <div class="chat-head mr-1">
                            </div>
                        @endif
                        <div style="max-width: 500px;">
                            <div class="card border-{{ $message->sender_id == Auth::id()? 'primary':'dark' }}">
                                <div class="card-body py-1 px-2">
                                    <p class="my-1">
                                        {!! nl2br(e($message->chat)) !!}
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <small>
                                    {{ \Carbon\Carbon::parse($message->created_at)->format('M d, Y  h:i A') }}    
                                    @if ( $message->sender_id == Auth::id() && !$loop->last && isset($message->seen) )
                                        <i class="fas fa-eye ml-1"></i>
                                    @endif
                                </small>
                            </div>
                            @if ( $message->sender_id == Auth::id() && $loop->last && isset($message->seen) )
                                <div class="d-flex justify-content-end">
                                    <small>
                                        seen <i class="fas fa-eye"></i>
                                    </small>
                                </div>
                            @endif
                        </div>
                        @if ( $message->sender_id == Auth::id() && $user_id_icon != $message->sender_id )
                            <div class="border border-primary bg-primary text-white rounded-circle chat-head ml-1 d-flex justify-content-center" >
                                <h4 class="my-auto">
                                    <strong>
                                        {{ strtolower($message->sender->firstname[0]) }}
                                    </strong>
                                </h4>
                            </div>
                            @php
                                $user_id_icon = $message->sender_id;
                            @endphp
                        @else
                            <div class="chat-head ml-1">
                            </div>
                        @endif
                    </div>
                @empty
                    @if ( $rid == Auth::id() )
                        <div class="alert alert-danger">
                            You can't send a message to your self.
                        </div>
                    @else
                        <div class="alert alert-info">
                            Start the conversation by sending a message.
                        </div>
                    @endif
                @endforelse
            </div>
        </div>
    </div>
    @if ( $rid != Auth::id() )
        <div>
            <hr class="mt-1 mb-2">
            <div class="mb-3">
                <div class="input-group">
                    <textarea wire:model.lazy='chat' wire:ignore.self class="form-control rounded border-primary" rows="1" 
                        aria-label="With textarea" placeholder="Message">
                    </textarea>
                    <div class="input-group-append ml-2 mb-auto">
                        <button wire:click='send' wire:loading.attr="disabled" class="btn btn-primary rounded" type="button"
                            @if ( $errors->has('chat') )
                                disabled
                            @endif
                            >
                            <i wire:loading.remove wire:target='send' class="fas fa-paper-plane"></i>
                            <i wire:loading wire:target='send' class="fas fa-circle-notch fa-spin"></i>
                        </button>
                    </div>
                </div>
                @error('chat') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
    @endif
</div>
