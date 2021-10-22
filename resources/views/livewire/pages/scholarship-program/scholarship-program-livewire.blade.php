<div>
    @isset($scholarship)
    <div class="row mt-1 p-1">
        <div class="card col-12 bg-secondary text-white border-secondary">
            <h2 class="m-2 row">
                <strong class="my-auto">
                    {{ $scholarship->scholarship }}
                </strong>
                
                <div class="mr-1 ml-auto d-flex flex-wrap">
                    @can('viewDashboard', $scholarship)
                        <a class="btn btn-light ml-1"
                            href="{{ route('scholarship.dashboard', [$scholarship->id]) }}">
                            <i class="fas fa-chart-pie"></i>
                            <strong>Dashboard</strong>
                        </a>
                    @endcan
                    @can('viewAny', [\App\Models\ScholarshipPost::class, $scholarship_id])
                        <a class="btn btn-light ml-1"
                            href="{{ route('scholarship.home', [$scholarship->id]) }}">
                            <i class="fas fa-newspaper"></i>
                            <strong>Home</strong>
                        </a>
                    @endcan
                    @can('viewAny', [\App\Models\ScholarshipScholar::class, $scholarship_id])
                        <a class="btn btn-light ml-1"
                            href="{{ route('scholarship.scholar', [$scholarship->id]) }}">
                            <i class="fas fa-user-graduate"></i>
                            <strong>Scholars</strong>
                        </a>
                    @endcan
                    @can('viewAny', [\App\Models\ScholarshipOfficer::class, $scholarship_id])
                        <a class="btn btn-light ml-1"
                            href="{{ route('scholarship.officer', [$scholarship->id]) }}">
                            <i class="fas fa-user-tie"></i>
                            <strong>Officers</strong>
                        </a>
                    @endcan
                    @can('viewAny', [\App\Models\ScholarshipRequirement::class, $scholarship_id])
                        <a class="btn btn-light ml-1"
                            href="{{ route('scholarship.requirement', [$scholarship->id]) }}">
                            <i class="fas fa-file-alt"></i>
                            <strong>Requirements</strong>
                        </a>
                    @endcan
                    @can('viewAny', [\App\Models\ScholarshipCategory::class, $scholarship_id])
                        <a class="btn btn-light ml-1"
                            href="{{ route('scholarship.category', [$scholarship->id]) }}">
                            <i class="fas fa-money-check-alt"></i>
                            <strong>Category</strong>
                        </a>
                    @endcan
                    @canany(['sendEmails', 'sendSMSes'], $scholarship)
                        <div class="dropdown ml-1">
                            <button class="btn btn-light dropdown-toggle" type="button" 
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <strong>More</strong>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                @can('sendEmails', $scholarship)
                                    <a class="dropdown-item" href="{{ route('scholarship.send.email', [$scholarship->id]) }}">
                                        Send Email
                                    </a>
                                @endcan
                                @can('sendSMSes', $scholarship)
                                    <a class="dropdown-item" href="{{ route('scholarship.send.sms', [$scholarship->id]) }}">
                                        Send SMS
                                    </a>
                                @endcan
                            </div>
                        </div>
                    @endcanany
                </div>
            </h2>
        </div>
    </div>
    @endisset
</div>
