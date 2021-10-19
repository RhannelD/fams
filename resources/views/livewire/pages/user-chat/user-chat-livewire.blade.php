<div class="flex-grow-1 d-flex flex-column">
@isset($chat_user)
    <nav class="navbar navbar-dark bg-primary">
        <a class="navbar-brand">
            <strong>
                {{ $chat_user->flname() }}
            </strong>
        </a>
    </nav>
    <div class="container-fluid flex-grow-1 d-flex flex-column position-relative">
        <div class="row flex-grow-1">
            <div class="col-12">
                <div class="mh-100">
                    @php
                        $user_id_icon = 0;
                    @endphp
                    @foreach ($messages as $message)
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
                                <div class="card border-{{ $message->sender_id == Auth::id()? 'primary':'dark' }}" 
                                    >
                                    <div class="card-body py-1 px-2">
                                        <p class="my-1">
                                            {!! nl2br(e($message->chat)) !!}
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <small>
                                        {{ \Carbon\Carbon::parse($message->created_at)->format('M d, Y  h:i A') }}    
                                    </small>
                                </div>
                            </div>
                            @if ( $message->sender_id == Auth::id() && $user_id_icon != $message->sender_id )
                                <div class="border border-dark rounded-circle chat-head ml-1 d-flex justify-content-center" >
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
                    @endforeach
                </div>
            </div>
        </div>
        <div>
            <hr class="mt-1 mb-2">
            <div class="input-group mb-3">
                <input type="text" class="form-control rounded border-primary" placeholder="Message">
                <div class="input-group-append ml-2">
                    <button class="btn btn-primary rounded" type="button">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endisset
</div>
