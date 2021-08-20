<div>
	<div class="row">
        <div class="col-12">

            @isset($category_id)
                <div class="form-group">
                    <label for="c_sr_code">Category ID</label>
                    <input type="text" class="form-control" id="c_sr_code" value="{{ $category_id }}" disabled>
                </div>
            @endisset

            <div class="form-group">
                <label for="c_category">Category</label>
                <input type="text" wire:model.lazy="category.category" class="form-control" id="c_category" placeholder="Category name">
                @error('category.category') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="c_amount">Amount</label>
                <input type="number" wire:model.lazy="category.amount" class="form-control" id="c_amount" placeholder="Amount">
                @error('category.amount') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

        </div>
    </div>
</div>