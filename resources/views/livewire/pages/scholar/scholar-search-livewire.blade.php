<div>
    
    <div class="d-flex justify-content-end my-1">
        {{ $scholars->links() }}
    </div> 

    <div class="table-responsive bg-white">

        <table class="table table-sm table-hover card-header student_table">
        
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Email</th>
                </tr>
            </thead>

            <tbody>

                @forelse($scholars as $scholar)
                    <tr class="rows" wire:click="info({{ $scholar->id }})">
                        <td class="text-nowrap">{{ $scholar->id }}</td>
                        <td class="text-nowrap">{{ $scholar->firstname }}</td>
                        <td class="text-nowrap">{{ $scholar->lastname }}</td>
                        <td class="text-nowrap">{{ $scholar->email }}</td>
                    </tr>
                @empty
                    <tr class="rows">
                        <td class="text-nowrap" colspan="4">
                            No Results
                        </td>
                    </tr>
                @endforelse
                
            </tbody>

        </table> 
    
    </div>

</div>