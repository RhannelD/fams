<div>
    <div class="d-flex justify-content-end my-1">
        {{ $scholars->links() }}
    </div> 

    <div class="table-wrap table-responsive">
        <table class="table myaccordion table-hover" id="accordion">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    @empty($category_id)
                        <th>Category</th>
                    @endempty
                    <th>Email</th>
                    <th>Phone</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($scholars as $scholar)
                    <tr data-toggle="collapse" data-target="#collapse{{ $scholar->user_id }}" aria-expanded="true" aria-controls="collapse{{ $scholar->user_id }}"
                        aria-expanded="false"
                        >
                        <th scope="row">{{ $scholar->user_id }}</th>
                        <td>{{ $scholar->firstname }} {{ $scholar->middlename }} {{ $scholar->lastname }}</td>
                        @empty($category_id)
                            <td>{{ $scholar->category }}</td>
                        @endempty
                        <td>{{ $scholar->email }}</td>
                        <td>{{ $scholar->phone }}</td>
                        <td>
                            <i class="fa" aria-hidden="true"></i>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" id="collapse{{ $scholar->user_id }}" data-parent="#accordion"
                            class="collapse acc"
                            >
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Porro iste, facere sunt sequi nostrum ipsa, amet doloremque magnam reiciendis tempore sapiente. Necessitatibus recusandae harum nam sit perferendis quia inventore natus.</p>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>