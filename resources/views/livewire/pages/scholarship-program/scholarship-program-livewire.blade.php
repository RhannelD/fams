<div>
    @isset($scholarship)
    <div class="row mt-1 p-1">
        <div class="card col-12 bg-secondary text-white border-secondary">
            <h2 class="m-2 row">
                <strong class="my-auto">
                    {{ $scholarship->scholarship }}
                </strong>
                
                <div class="mr-1 ml-auto">
                    @can('viewDashboard', $scholarship)
                        <a class="btn btn-light"
                            href="{{ route('scholarship.dashboard', [$scholarship->id]) }}">
                            <i class="fas fa-chart-pie"></i>
                            <strong>Dashboard</strong>
                        </a>
                    @endcan
                    @can('viewAny', [\App\Models\ScholarshipPost::class, $scholarship_id])
                        <a class="btn btn-light"
                            href="{{ route('scholarship.home', [$scholarship->id]) }}">
                            <i class="fas fa-newspaper"></i>
                            <strong>Home</strong>
                        </a>
                    @endcan
                    @can('viewAny', [\App\Models\ScholarshipScholar::class, $scholarship_id])
                        <a class="btn btn-light"
                            href="{{ route('scholarship.scholar', [$scholarship->id]) }}">
                            <i class="fas fa-user-graduate"></i>
                            <strong>Scholars</strong>
                        </a>
                    @endcan
                    @can('viewAny', [\App\Models\ScholarshipOfficer::class, $scholarship_id])
                        <a class="btn btn-light"
                            href="{{ route('scholarship.officer', [$scholarship->id]) }}">
                            <i class="fas fa-user-tie"></i>
                            <strong>Officers</strong>
                        </a>
                    @endcan
                    @can('viewAny', [\App\Models\ScholarshipRequirement::class, $scholarship_id])
                        <a class="btn btn-light"
                            href="{{ route('scholarship.requirement', [$scholarship->id]) }}">
                            <i class="fas fa-file-alt"></i>
                            <strong>Requirements</strong>
                        </a>
                    @endcan
                    @can('viewAny', [\App\Models\ScholarshipCategory::class, $scholarship_id])
                        <a class="btn btn-light"
                            href="{{ route('scholarship.category', [$scholarship->id]) }}">
                            <i class="fas fa-money-check-alt"></i>
                            <strong>Category</strong>
                        </a>
                    @endcan
                </div>
            </h2>
        </div>
    </div>
    @endisset
</div>
