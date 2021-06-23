<div>
    
    <div class="d-flex justify-content-end my-1">
        {{ $scholars->links() }}
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

                @foreach ($scholars as $scholar)
                    <tr class="rows">
                        <td class="text-nowrap">{{ $scholar->id }}</td>
                        <td class="text-nowrap">{{ $scholar->firstname }}</td>
                        <td class="text-nowrap">{{ $scholar->lastname }}</td>
                        <td class="text-nowrap">{{ $scholar->email }}</td>
                    </tr>
                @endforeach

            </tbody>

        </table> 
    
    </div>

</div>