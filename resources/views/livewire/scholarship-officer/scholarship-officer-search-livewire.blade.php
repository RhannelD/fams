<div>
    
    <div class="d-flex justify-content-end my-1">
        {{ $officers->links() }}
    </div> 

    <div class="card table-responsive">

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

                @foreach ($officers as $officer)
                    <tr class="rows" wire:click="info({{ $officer->id }})">
                        <td class="text-nowrap">{{ $officer->id }}</td>
                        <td class="text-nowrap">{{ $officer->firstname }}</td>
                        <td class="text-nowrap">{{ $officer->lastname }}</td>
                        <td class="text-nowrap">{{ $officer->email }}</td>
                    </tr>
                @endforeach

            </tbody>

        </table> 
    
    </div>

</div>