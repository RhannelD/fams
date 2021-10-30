<div>
    @livewire('add-ins.scholarship-program-livewire', [$scholarship_id, 'category'], key('page-tabs-'.time().$scholarship_id))
    
	<div class="mb-1 mx-3">
		<div class="d-flex mt-2">
            <h3 class="ml-3 my-auto py-1">
                <strong>Categories</strong>
            </h3>
            @can( 'create', [\App\Models\ScholarshipCategory::class, $scholarship_id] )
                <button wire:click="$emit('unset_category')" class="btn btn-success ml-auto mr-0" type="button" data-toggle="modal" data-target="#category_form">
                    Create Category
                </button>
            @endcan
        </div>
	</div>

    <div class="mx-md-3 mx-1 row mt-3">
        @forelse ($categories as $category)
            <div class="col-xl-3 col-lg-3 col-sm-6 my-2">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="my-0">{{ $category->category }}</h3>

                            @canany( ['update', 'delete'], $category )
                                <div class="dropdown mr-0 ml-auto" id="dropdown-action-category-{{ $category->id }}">
                                    <span id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </span>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @can( 'update', $category )
                                            <a wire:click="$emit('set_category', {{ $category->id }})" class="dropdown-item" type="button" data-toggle="modal" data-target="#category_form">
                                                <i class="fas fa-pen-square mr-1"></i>
                                                Edit Category
                                            </a>
                                        @endcan
                                        @can( 'delete', $category )
                                            <a wire:click="delete_category_confirmation({{ $category->id }})" class="dropdown-item">
                                                <i class="fas fa-trash mr-1"></i>
                                                Delete Category
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            @endcanany
                        </div>
                        <table class="col-12">
                            <tr>
                                <td>Amount:</td>
                                <td class="text-right">
                                    <span class="ml-1">
                                        Php {{ $category->amount }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Scholars:</td>
                                <td class="text-right">
                                    <span class="ml-1">
                                        {{ $category->scholars->count() }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <hr class="my-0">
                                </td>
                            </tr>
                            <tr>
                                <td>Total Amount:</td>
                                <td class="text-right">
                                    <span class="ml-1">
                                        Php {{ $category->amount*$category->scholars->count() }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        @empty 
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 my-2">
                <div class="card">
                    <div class="card-body">
                        No results found...
                    </div>
                </div>
            </div>
        @endforelse
    </div>

	<div>
        @can( 'create', [\App\Models\ScholarshipCategory::class, $scholarship_id] )    
            <div wire:ignore.self class="modal fade category_form" id="category_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal" role="document">
                    @livewire('scholarship-category.scholarship-category-edit-livewire', [$scholarship_id]))
                </div>
            </div>
        @endcan
    </div>


    <script>

        window.addEventListener('swal:confirm:delete_category', event => { 
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

		window.addEventListener('category-form', event => {
			$(".category_form").modal(event.detail.action);
		});
		
    </script>
</div>
