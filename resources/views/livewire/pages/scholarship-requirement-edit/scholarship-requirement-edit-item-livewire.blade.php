<div>
    <div class="card mb-3 shadow requirement-item-hover">
        <div class="card-body">
            <div class="form-group">
                <input wire:model.lazy="item.item" class="form-control form-control-lg" type="text" 
                    placeholder=".form-control-lg">
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
                        <label for="type_{{ $item->id }}">Type</label>
                        <select wire:model.lazy="item.type" class="form-control" id="type_{{ $item->id }}">
                            <option value="question">Answer</option>
                            <option value="file">File Upload</option>
                            <option value="radio">Radio</option>
                            <option value="check">Checkbox</option>
                        </select>
                        @error('item.type') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-12">
                    @switch($item->type)
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
                            <div class="form-group">
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-file"></i>
                                        </span>
                                    </div>
                                    <div class="form-control">File Upload</div>
                                </div>
                            </div>
                            @break
                        @case('radio')
                        @case('check')
                            <div class="form-row">
                                <div class="form-group col-11">
                                    @foreach ($options as $option)

                                        @livewire('scholarship-requirement-edit-item-option-livewire', [$option->id, $item->type], key('item-option-'.time().$option->id))

                                    @endforeach
                                    
                                </div>
                                
                                <div class="form-group col-1">
                                    <button wire:click="add_item_option" class="btn btn-success">
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
    </script>
</div>
