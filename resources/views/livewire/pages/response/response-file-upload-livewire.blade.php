<div>
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

    @error('file') <span class="text-danger">{{ $message }}</span> @enderror
    
    @isset( $response_file ) 
        <hr class="my-2">
        <a href="{{ Storage::disk('files')->url($response_file->file_url) }}" target="blank">

            <div class="input-group mb-1 item-hover">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-primary text-white border-primary">
                        
                        @php
                            $file_extension = pathinfo($response_file->file_url, PATHINFO_EXTENSION);
                        @endphp
                        @include('livewire.pages.response.response-file-upload-icon-type-livewire')
    
                    </span>
                </div>
                <input type="text" class="form-control bg-white border-primary" value="{{ $response_file->file_name }}" readonly>
            </div>
            
        </a>
    @endisset

</div>
