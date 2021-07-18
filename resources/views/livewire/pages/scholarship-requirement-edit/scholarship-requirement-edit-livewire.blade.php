<div>
    <div class="row mt-1 p-1">
        <div class="card col-12 bg-secondary text-white border-secondary">
            <h2 class="m-2 row">
                <strong class="my-auto">
                    {{ $scholarship->scholarship }} -  Requirement
                </strong>
                
                <div class="mr-1 ml-auto">
                    <a class="btn btn-light"
                        href="{{ route('scholarship.program', [$scholarship->id, 'home']) }}">
                        <i class="fas fa-newspaper"></i>
                        <strong>Home</strong>
                    </a>
                    <a class="btn btn-light"
                        href="{{ route('scholarship.program', [$scholarship->id, 'requirement']) }}">
                        <i class="fas fa-file-alt"></i>
                        <strong>Requirements</strong>
                    </a>
                    <a class="btn btn-light"
                        href="{{ route('scholarship.program', [$scholarship->id, 'requirement', $requirement->id]) }}">
                        <i class="fas fa-arrow-circle-left"></i>
                        <strong>View</strong>
                    </a>
                </div>
            </h2>
        </div>
    </div>

    <hr>
    <div class="row">
        <div class="col-12 col-md-3 mb-2">
            
            <div class="card shadow mb-2 requirement-item-hover">
                <div class="card-body">
                    <div class="saving_loading">
                        <div class="input-group saving_state my-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text border-info bg-info text-white">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control border-info bg-white" value=" Saving ..." readonly>
                        </div>
                        <div class="input-group saved_state my-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text border-success bg-success text-white">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control border-success bg-white" value=" Saved" readonly>
                        </div>
                    </div>
                </div>
            </div>

            @livewire('scholarship-requirement-activate-livewire', [$requirement->id], key('activate-livewire-'.time().$requirement->id))

            <div class="card shadow mb-2 requirement-item-hover">
                <div class="card-body">
                    <div class="form-group">
                        <label for="promote_{{ $requirement->id }}">Requirement for</label>
                        <select wire:model.lazy="requirement.promote" class="form-control" id="promote_{{ $requirement->id }}">
                            <option value="1">Applicatants</option>
                            <option value="0">Old Scholars</option>
                        </select>
                        @error('requirement.promote') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                
                    <div class="form-group mb-0">
                        <label for="">Requirement for category</label>
                        @foreach ($categories as $category)
                            <div class="input-group mb-1">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <input type="checkbox"
                                            @isset($category->category_id)
                                                checked
                                            @endisset
                                            wire:click="toggle_category({{ $category->id }})"
                                            >
                                    </div>
                                </div>
                                <input type="text" class="form-control bg-white" readonly
                                    value="{{ $category->category }}"
                                    >
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <hr>
        </div>

        <div class="col-12 col-md-9 order-md-first">
            <div class="card bg-white border-dark mb-4 shadow">
                <div class="card-body border-primary">
                    <div class="form-group">
                        <label for="requirement"><h5><strong>Requirement Title</strong></h5></label>
                        <input wire:model.lazy="requirement.requirement" class="form-control form-control-lg" type="text" 
                            placeholder="Requirement Title" id='requirement'>
                        @error('requirement.requirement') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-0">
                        <label for="description">Description</label>
                        <textarea wire:model.lazy="requirement.description" class="form-control" id="description" rows="3"></textarea>
                        @error('requirement.description') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>  
                </div>
            </div>
        
            <div class="row">
                <div class="col-12"  wire:sortable="update_requirement_order">
                    @foreach ($items as $item)
                        <div wire:sortable.item="{{ $item->id }}" wire:key="item-{{ $item->id }}" class="div_item_id_sort_{{ $item->id }}">
                            @livewire('scholarship-requirement-edit-item-livewire', [$item->id], key('item-'.time().$item->id))
                        </div>
                    @endforeach
                </div>
                <div class="col-sm-12 offset-sm-0 col-md-10 offset-md-1">
                    <div class="card mb-3 shadow requirement-item-hover">
                        <div class="card-body">
                            <button wire:click="add_item" class="btn btn-primary btn-block"> 
                                Add New Item 
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $('.saving_loading .saving_state').hide();
        $(".requirement-item-hover").hover(function () {
            $(this).toggleClass("shadow-lg");
        });

        window.addEventListener('delete_item_div', event => { 
            $( '.div_item_id_'+event.detail.div_class ).fadeOut( 500, function(){
                $( '.div_item_id_sort_'+event.detail.div_class ).remove();
            });
        });

        window.onload = function() {
            Livewire.hook('message.sent', () => {
                $('.saving_loading .saved_state').hide();
                $('.saving_loading .saving_state').show();
                $('.loadingggg').show();
            })
            Livewire.hook('message.processed', (message, component) => {
                $('.saving_loading .saving_state').hide();
                $('.saving_loading .saved_state').show();
            })
        }
    </script>
</div>