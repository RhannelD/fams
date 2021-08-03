<div>
    <div class="input-group mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroupFileAddon01">
                @if ( isset($photo) )
                    @php
                        $file_mine_type = $photo->getMimeType();
                    @endphp
                    @include('livewire.pages.response.response-file-upload-icon-type-livewire')
                @else
                    <i class="fas fa-file-upload"></i>
                @endif
            </span>
        </div>
        <div class="custom-file">
            <input wire:model="photo" type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
            <label class="custom-file-label" for="inputGroupFile01">
                @if ($photo)
                    {{ $photo->getClientOriginalName() }}
                @elseif ($requirement_item->type == 'grade')
                    Grade Upload
                @elseif ($requirement_item->type == 'cor')
                    COR Upload
                @else
                    File Upload
                @endif
            </label>
        </div>
    </div>
    @error('photo') <span class="text-danger">{{ $message }}</span> @enderror
</div>
