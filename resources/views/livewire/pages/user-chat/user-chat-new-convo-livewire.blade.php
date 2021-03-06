<nav class="navbar navbar-dark bg-primary">
    <a class="navbar-brand">
        <strong>
            New Message
        </strong>
    </a>
</nav>
<div class="container-fluid">
    <div class="row">
        <div class="input-group rounded offset-md-6 col-md-6 my-2">
            <input type="search" class="form-control rounded" placeholder="Search People" wire:model.debounce.1000ms='search'/>
            <span wire:click='$refresh' class="input-group-text border-0 ml-1">
                <i class="fas fa-search"></i>
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            @if ( count($search_users) > 0 )
                <div class="d-flex justify-content-end my-1">
                    {{ $search_users->links() }}
                </div>
                <table class="table table-sm table-hover">
                    <tr>
                        <th>#</th>
                        <th>Name</th> 
                        <th>Email</th>
                    </tr>
                    @forelse ($search_users as $search_user)
                        <tr wire:click="$emit('set_receiver', {{ $search_user->id }})">
                            <th>
                                {{ ( ($loop->index + 1) + ( (15 * $page ) - 15) ) }}
                            </th>
                            <td>
                                {{ $search_user->flname() }}
                            </td>
                            <td>
                                {{ $search_user->email }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">None</td>
                        </tr>
                    @endforelse
                </table>
            @endif
        </div>
    </div>
</div>
