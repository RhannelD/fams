<div class="row mx-0">
@isset($agreement_id)
    <div class="col-sm-12 offset-sm-0 col-md-1 px-0">
        <hr class="d-block d-md-none">
        <div class="dropleft">
            <button type="button" class="btn btn-info float-right text-white ml-1 rounded-circle shadow mb-1 requirement-item-hover" 
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                style="min-width: 39px; min-height: 39px; max-width: 39px; max-height: 39px;"
                >
                <i class="fas fa-bars"></i>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item"
                    type="button" data-toggle="modal" data-target="#agreementDuplicationModal">
                    Duplicate
                </a>
            </div>
        </div>
        <button class="btn btn-danger float-right ml-1 rounded-circle shadow mb-1 requirement-item-hover"
            wire:click="delete_confirmation"
            style="min-width: 39px; min-height: 39px; max-width: 39px; max-height: 39px;">
            <i class="fas fa-trash"></i>
        </button>     
    </div>
    
    @livewire('scholarship-requirement-edit-agreement-duplicate-livewire', [$agreement_id], key('agreement-duplicate-'.time()))

    <div class="card mb-5 shadow requirement-item-hover col-sm-12 offset-sm-0 col-md-10 offset-md-1 order-md-first">
        <div class="card-body mx-0 px-0">
            <div class="form-group mb-0">
                <label for="agreement">Terms and Conditions</label>
                <div wire:ignore>
                    <textarea wire:model.lazy="agreement" class="form-control" id="agreement" rows="3"></textarea>
                </div>
                @error('agreement') <span class="text-danger">{{ $message }}</span> @enderror
            </div>  
            <div class="d-flex justify-content-end mt-2">
                <button class="btn btn-info text-white">
                    Duplicate Another Terms and Conditions
                </button>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('swal:confirm:delete_agreement_confirmation', event => { 
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

        $( document ).ready(function() {
            let EditorInstance;
            ClassicEditor.create( document.querySelector( '#agreement' ), {
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
                    EditorInstance = editor;
                    editor.ui.focusTracker.on( 'change:isFocused', ( evt, name, isFocused ) => {
                        if ( !isFocused ) {
                            @this.set('agreement', editor.getData());
                        }
                    } );
                } )
                .catch( error => {
                    console.error( error );
                } );

            window.addEventListener('refreshing', event => { 
                EditorInstance.setData(event.detail.description);
            });
        });
    </script>
@endisset
</div>
    