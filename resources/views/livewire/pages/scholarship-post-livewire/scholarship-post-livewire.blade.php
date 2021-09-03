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

                        @if ( count($requirements) != 0 )
                            <div class="form-group d-flex mb-1">
                                <button wire:click="show_requirements" type="button" class="btn btn-info mr-0 ml-auto text-white">
                                    Add Requirement Link
                                </button>
                            </div>
                        @endif
                            
                        @php  $displayed = 0;  @endphp
                        @foreach ($requirements as $requirement)  
                            @if ( in_array($requirement->id, $added_requirements)  )    
                                @php  $displayed++;  @endphp  
                                <div class="input-group mb-1">
                                    <input type="text" class="form-control bg-white" value="{{ $requirement->requirement }}" readonly>
                                    <div class="input-group-append">
                                        <button wire:click="remove_requirement({{ $requirement->id }})" class="btn btn-danger" type="button">
                                            <i class="fas fa-minus-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        @if ( $displayed == 0 )
                            <div class="input-group mb-1">
                                <input type="text" class="form-control bg-white" value="None" readonly>
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

                        @php  $displayed = 0;  @endphp
                        @foreach ($requirements as $requirement)  
                            @if ( !in_array($requirement->id, $added_requirements)  )    
                                @php  $displayed++;  @endphp  
                                <div class="input-group mb-1">
                                    <input type="text" class="form-control bg-white" value="{{ $requirement->requirement }}" readonly>
                                    <div class="input-group-append">
                                        <button wire:click="add_requirement({{ $requirement->id }})" class="btn btn-success" type="button">
                                            <i class="fas fa-plus-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        @if ( $displayed == 0 )
                            <div class="input-group mb-1">
                                <input type="text" class="form-control bg-white" value="None" readonly>
                            </div>
                        @endif

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
