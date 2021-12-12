<div wire:ignore.self class="modal fade" id="post_something" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="post_something_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form class="modal-content" wire:submit.prevent="save">
            <div class="modal-header">
                <h5 class="modal-title" id="post_something_label">Post</h5>
                <button type="button" class="close close_post_modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tab-content" id="pills-tabContent">

                    <div role="tabpanel"
                        @if (!$show_requirement)
                            class="tab-pane fade show active" 
                        @else
                            class="tab-pane fade" 
                        @endif
                        >
                        <div class="form-group">
                            <input wire:model.lazy="post.title" type="text" class="form-control form-control-lg" placeholder="Title (Optional)">
                            @error('post.title') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="post_post">Post</label>
                            <div wire:ignore>
                                <textarea wire:model.lazy="post.post" class="form-control" id="post_post" rows="5" placeholder="Post something..."></textarea>
                            </div>
                            @error('post.post') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group form-check">
                            <input wire:model.lazy="post.promote" type="checkbox" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">
                                Global
                                (
                                    @if ($post->promote)
                                        This will be visible to new applicants
                                    @else
                                        This not visible to new applicants
                                    @endif
                                )
                            </label>
                            @error('post.promote') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        @if ( count($requirements) != 0 )
                            <div class="form-group d-flex mb-1">
                                <button wire:click="show_requirements" type="button" class="btn btn-info mr-0 ml-auto text-white">
                                    Add Requirement Link
                                </button>
                            </div>
                        @endif
                            
                        @forelse ($display_requirements as $requirement)  
                            <div class="input-group mb-1">
                                <input type="text" class="form-control bg-white" value="{{ $requirement->requirement }}" readonly>
                                <div class="input-group-append">
                                    @if ( !$requirement->promote && $post->promote )
                                        @php
                                            $match = true;
                                        @endphp
                                        <a class="btn btn-outline-danger" data-toggle="collapse" href="#collapse_{{ $requirement->id }}" role="button" 
                                            aria-expanded="false" aria-controls="collapse_{{ $requirement->id }}">
                                            <i class="fas fa-exclamation-circle"></i>
                                        </a>
                                    @endif
                                    <button wire:click="remove_requirement({{ $requirement->id }})" class="btn btn-danger" type="button">
                                        <i class="fas fa-minus-circle"></i>
                                    </button>
                                </div>
                            </div>
                            @if ( isset($match) && $match )
                                <div class="collapse" id="collapse_{{ $requirement->id }}">
                                    <div class="mx-1 mb-2">
                                        <span class="text-danger">
                                            This requirement is for old scholars.
                                        </span>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="input-group mb-1">
                                <input type="text" class="form-control bg-white" value="None" readonly>
                            </div>
                        @endforelse
                        @if ( isset($match) && $match )
                            <hr class="my-2">
                            <div class="alert alert-warning mt-2">
                                The post has been set as global.<br>
                                Requirements for old scholars are not accessible to new applicants.
                            </div>
                        @endif
                    </div>

                    <div role="tabpanel"
                        @if ($show_requirement)
                            class="tab-pane fade show active" 
                        @else
                            class="tab-pane fade" 
                        @endif
                        >

                        <div class="d-flex align-content-start flex-wrap my-1">
                            <div class="d-flex mr-0 mr-sm-auto">
                                <div class="input-group rounded">
                                    <input type="search" class="form-control rounded" placeholder="Search Requirements" wire:model.debounce.1000ms='search'/>
                                    <span wire:click="$refresh" class="input-group-text border-0 mx-1">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                            </div>
                            {{ $requirements->links() }}
                        </div> 
                        @forelse ($requirements as $requirement)
                            <div class="input-group mb-1">
                                <input type="text" class="form-control bg-white" value="{{ $requirement->requirement }}" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        {{ $requirement->req_year_sem() }}
                                    </span>
                                    <button wire:click="add_requirement({{ $requirement->id }})" class="btn btn-success" type="button">
                                        <i class="fas fa-plus-circle"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="input-group mb-1">
                                <input type="text" class="form-control bg-white" value="None" readonly>
                            </div>
                        @endforelse

                        <div class="form-group d-flex">
                            <button wire:click="show_requirements" type="button" class="btn btn-info mr-0 ml-auto text-white">
                                Back
                            </button>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Post </button>
            </div>
        </form>
    </div>
    
    <script>
        window.addEventListener('close_post_modal', event => { 
            $('.close_post_modal').click();
        });

        $( document ).ready(function() {
            $('[data-toggle="popover"]').popover()
            $('.popover-dismiss').popover({
                trigger: 'focus'
            });

            ClassicEditor.create( document.querySelector( '#post_post' ), {
                toolbar: {
                    items: [
                        'heading',
                        '|',
                        'bold',
                        'italic',
                        'link',
                        'bulletedList',
                        'numberedList',
                        '|',
                        'outdent',
                        'indent',
                        '|',
                        'undo',
                        'redo',
                    ]
                },
                language: 'en',
                    licenseKey: '',
                } )
                .then( editor => {
                    editor.ui.focusTracker.on( 'change:isFocused', ( evt, name, isFocused ) => {
                        if ( !isFocused ) {
                            @this.set('post.post', editor.getData());
                        }
                    } );
                } )
                .catch( error => {
                    console.error( error );
                } );

                $.fn.modal.Constructor.prototype._enforceFocus = function() {
                    $( '#post_something' ).modal( {
                        focus: false
                    } );
                };
        });
    </script>
</div>
