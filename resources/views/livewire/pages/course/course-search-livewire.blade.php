<div>
    <div class="d-flex justify-content-end my-1">
        {{ $courses->links() }}
    </div> 

    <div class="card table-responsive mb-3">
        <table class="table table-sm table-hover card-header">
            <thead class="thead-dark">
                <tr>
                    <th>Course</th>
                    <th class="text-center">Scholars</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($courses as $course)
                    <tr class="rows">
                        <td class="text-nowrap">{{ $course->course }}</td>
                        <td class="text-center">{{ $course->scholars->count() }}</td>
                        <td class="text-center text-nowrap">
                            @can( 'update', $course )
                                <button onclick="set_course({{ $course->id }})" class="btn btn-sm btn-secondary" type="button" data-toggle="modal" data-target="#course-modal">
                                    <i class="fas fa-edit"></i>
                                </button>
                            @endcan
                                
                            @can( 'delete', $course )
                                <button wire:click='delete_confirm({{ $course->id }})' class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-nowrap" colspan="4">
                            No Results
                        </td>
                    </tr>
                @endforelse 
            </tbody>
        </table> 
    </div>
</div>
