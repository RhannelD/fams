<div>
    @if ( !isset( $response->submit_at ) )
        <div class="input-group mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupFileAddon01">

                    <div wire:target="file" wire:loading.remove>
                        <i class="fas fa-file-upload"></i>
                    </div>

                    <div wire:target="file" wire:loading>
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>

                </span>
            </div>

            <div class="custom-file">

                <input wire:model="file" type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">

                <label class="custom-file-label" for="inputGroupFile01">
                    @if ($requirement_item->type == 'grade')
                        Grade Upload
                    @elseif ($requirement_item->type == 'cor')
                        COR Upload
                    @else
                        File Upload
                    @endif
                </label>

            </div>
        </div>
    @endif


    @error('file') <span class="text-danger">{{ $message }}</span> @enderror
    
    @isset( $response_file ) 
        <hr class="my-2">

        <div class="d-flex">
            <div class="mr-1 bd-highlight my-0 btn-block">
                <a href="{{ Storage::disk('files')->url($response_file->file_url) }}" target="blank">   
                    <div class="input-group mb-1 item-hover">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-primary text-white border-primary">
        
                                @if ( $response_file->if_file_exist() )
                                    @php
                                        $file_extension = $response_file->get_file_extension();
                                    @endphp
                                    @include('livewire.pages.response.response-file-upload-icon-type-livewire')
                                @else
                                    <i class="fas fa-exclamation-circle"></i>
                                @endif
        
                            </span>
                        </div>
                        <input type="text" class="form-control bg-white border-primary rounded-right" value="{{ $response_file->file_name }}" readonly>
        
                    </div>     
                </a>
            </div>

            @if ( !isset( $response_file->response->submit_at ) )
                <h6 class="ml-1 mr-0 bd-highlight my-0">
                    <button wire:click="delete({{ $response_file->id }})"  wire:loading.attr="disabled" class="btn btn-danger ml-1">
                        <i wire:loading.remove wire:target="delete" class="fas fa-minus-circle"></i>
                        <i wire:loading wire:target="delete" class="fas fa-spinner fa-spin"></i>
                    </button>
                </h6>
            @endif
        </div>
    @endisset

</div>
