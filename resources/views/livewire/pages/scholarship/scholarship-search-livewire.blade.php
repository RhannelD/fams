<div>
    
    <div class="d-flex justify-content-end my-1">
        {{ $scholarships->links() }}
    </div> 

    <div class="card table-responsive">

        <table class="table table-sm table-hover card-header student_table">
        
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Scholarship Program</th>
                </tr>
            </thead>

            <tbody>

                @forelse($scholarships as $scholarship)
                    <tr class="rows" wire:click="info({{ $scholarship->id }})">
                        <td class="text-nowrap">{{ $scholarship->id }}</td>
                        <td class="text-nowrap">{{ $scholarship->scholarship }}</td>
                    </tr>
                @empty
                    <tr class="rows">
                        <td class="text-nowrap" colspan="2">
                            No Results
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table> 
    
    </div>

</div>