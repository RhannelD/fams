<div class="row mx-0 div_item_id_{{ $item_id }}">
@isset($requirement_item)
    <div class="col-sm-12 offset-sm-0 col-md-1 px-0">
        <hr class="d-block d-md-none">
        <button class="btn btn-primary float-right ml-1 rounded-circle shadow mb-1 requirement-item-hover" wire:sortable.handle
            style="min-width: 39px; min-height: 39px; max-width: 39px; max-height: 39px;">
            <i class="fas fa-sort"></i>
        </button>
        <button class="btn btn-danger float-right ml-1 rounded-circle shadow mb-1 requirement-item-hover"
            wire:click="delete_confirmation"
            style="min-width: 39px; min-height: 39px; max-width: 39px; max-height: 39px;">
            <i class="fas fa-trash"></i>
        </button>
    </div>
    
    <div class="card mb-3 shadow requirement-item-hover col-sm-12 offset-sm-0 col-md-10 offset-md-1 order-md-first">
        <div class="card-body mx-0 px-0">
            <div class="form-group">
                <input wire:model.lazy="item.item" class="form-control form-control-lg" type="text" 
                    placeholder="Item">
                @error('item.item') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-row">
                <div class="col-9">
                    <div class="form-group">
                        <label for="description">Note (Optional)</label>
                        <textarea wire:model.lazy="item.note" class="form-control" id="description" rows="2"></textarea>
                        @error('item.note') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="type_{{ $requirement_item->id }}">Type</label>
                        <select wire:model.lazy="item.type" class="form-control" id="type_{{ $requirement_item->id }}">
                            <option value="question">Answer</option>
                            <option value="file">File Upload</option>
                            <option value="radio">Radio</option>
                            <option value="check">Checkbox</option>
                            <option value="cor">COR Upload</option>
                            <option value="grade">Grade Upload</option>
                        </select>
                        @error('item.type') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-12">
                    @switch($requirement_item->type)
                        @case('question')
                            <div class="form-group">
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-question"></i>
                                        </span>
                                    </div>
                                    <div class="form-control">Answer</div>
                                </div>
                            </div>
                            @break
                        @case('file')
                        @case('cor')
                        @case('grade')
                            <div class="form-group">
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-file"></i>
                                        </span>
                                    </div>
                                    <div class="form-control">
                                        @if($requirement_item->type == 'grade')
                                            Grade Upload
                                        @elseif ($requirement_item->type == 'cor')
                                            COR Upload
                                        @else
                                            File Upload
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @break
                        @case('radio')
                        @case('check')
                            <div class="form-row">
                                <div class="form-group col-sm-12 col-md-11 mb-1">
                                    @foreach ($requirement_item->options as $option)

                                        @livewire('scholarship-requirement-edit.scholarship-requirement-edit-item-option-livewire', [$option->id, $requirement_item->type], key('item-option-'.time().$option->id))

                                    @endforeach
                                    
                                </div>
                                
                                <div class="form-group col-sm-12 col-md-1">
                                    <button wire:click="add_item_option" class="btn btn-success float-right">
                                        <i class="fas fa-plus-circle"></i>
                                    </button>
                                </div>
                            </div>
                            @break
                    @endswitch
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('delete_option_div', event => { 
            $( '.item_option_id_'+event.detail.div_class ).fadeOut( 500 );
        });

        window.addEventListener('swal:confirm:delete_confirmation_{{ $requirement_item->id }}', event => { 
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
    </script>
@endisset
</div>
