<div>
    @isset($scholarship)
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark-grey border-bottom-0">
        <button onclick="menu_toggle()" class="btn btn-outline-light disabled" id="menu-toggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="navbar-brand ml-2 font-weight-bold">
            {{ $scholarship->scholarship }}
        </div>

        <button type="" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse1">
            <i class="fas fa-ellipsis-v"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse1">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <strong>
                            @if ( Auth::user()->is_admin() )
                                <i class="fas fa-user-cog"></i>
                            @elseif ( Auth::user()->is_officer() )
                                <i class="fas fa-chalkboard-teacher"></i>
                            @else
                                <i class="fas fa-user-graduate"></i>
                            @endif
                            {{ Auth::user()->firstname . ' ' . Auth::user()->lastname }} 
                        </strong>
                        @if ( Auth::user()->get_invite_and_unseen_chat_count() )
                            <span class="badge badge-danger">
                                {{ Auth::user()->get_invite_and_unseen_chat_count() }}
                            </span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-cyan" aria-labelledby="navbarDropdownMenuLink-4">
                        <a class="dropdown-item" href="{{ route('my.account') }}">
                            <i class="fas fa-user-circle"></i>
                            My account
                        </a>
                        <a class="dropdown-item" href="{{ route('user.chat') }}">
                            <i class="fas fa-comments"></i>
                            Messages
                            @if ( Auth::user()->get_unseen_chat_count() )
                                <span class="badge badge-danger">
                                    {{ Auth::user()->get_unseen_chat_count() }}
                                </span>
                            @endif
                        </a>
                        @if ( !Auth::user()->is_admin() )
                            <a class="dropdown-item" 
                                @if ( Auth::user()->is_officer() )
                                    href="{{ route('invite.officer') }}"
                                @else
                                    href="{{ route('invite.scholar') }}"
                                @endif
                                >
                                <i class="fas fa-envelope"></i>
                                Invites 
                                @if ( Auth::user()->get_invite_count() )
                                    <span class="badge badge-danger">
                                        {{ Auth::user()->get_invite_count() }}
                                    </span>
                                @endif
                            </a>
                        @endif
                        @livewire('add-ins.logout-livewire')
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <nav class="navbar navbar-expand-lg navbar-light bg-light-grey justify-content-center">
        <ul class="nav">
            @can('viewDashboard', $scholarship)
                <li class="nav-item"> 
                    <a class="nav-link text-white{{ $active == 'dashboard'? '': '-50' }}" href="{{ route('scholarship.dashboard', [$scholarship->id]) }}">
                        <i class="fas fa-chart-pie"></i>
                        Dashboard
                    </a>
                </li>
            @endcan
            @can('viewAny', [\App\Models\ScholarshipPost::class, $scholarship_id])
                <li class="nav-item">
                    <a class="nav-link text-white{{ $active == 'home'? '': '-50' }}" href="{{ route('scholarship.home', [$scholarship->id]) }}">
                        <i class="fas fa-newspaper"></i>
                        Home
                    </a>
                </li>
            @endcan
            @can('viewAny', [\App\Models\ScholarshipScholar::class, $scholarship_id])
                <li class="nav-item">
                    <a class="nav-link text-white{{ $active == 'scholars'? '': '-50' }} text-nowrap" href="{{ route('scholarship.scholar', [$scholarship->id]) }}">
                        <i class="fas fa-user-graduate"></i>
                        Scholars
                        @if ( $scholarship->get_num_of_scholars() )
                            <span class="badge badge-primary">
                                {{ $scholarship->get_num_of_scholars() }}
                            </span>
                        @endif
                    </a>
                </li>
            @endcan
            @can('viewAny', [\App\Models\ScholarshipOfficer::class, $scholarship_id])
                <li class="nav-item">
                    <a class="nav-link text-white{{ $active == 'officers'? '': '-50' }}" href="{{ route('scholarship.officer', [$scholarship->id]) }}">
                        <i class="fas fa-user-tie"></i>
                        Officers
                    </a>
                </li>
            @endcan
            @can('viewAny', [\App\Models\ScholarshipRequirement::class, $scholarship_id])
                <li class="nav-item">
                    <a class="nav-link text-white{{ $active == 'applications'? '': '-50' }} text-nowrap" href="{{ route('scholarship.application', [$scholarship->id]) }}">
                        <i class="fas fa-file-alt"></i>
                        Applications
                        @if ( $scholarship->get_num_of_pending_application_responses() )
                            <span class="badge badge-primary">
                                {{ $scholarship->get_num_of_pending_application_responses() }}
                            </span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white{{ $active == 'renewals'? '': '-50' }} text-nowrap" href="{{ route('scholarship.requirement', [$scholarship->id]) }}">
                        <i class="fas fa-file-alt"></i>
                        Renewals
                        @if ( $scholarship->get_num_of_pending_renewal_responses() )
                            <span class="badge badge-primary">
                                {{ $scholarship->get_num_of_pending_renewal_responses() }}
                            </span>
                        @endif
                    </a>
                </li>
            @endcan
            @can('viewAny', [\App\Models\ScholarshipCategory::class, $scholarship_id])
                <li class="nav-item">
                    <a class="nav-link text-white{{ $active == 'category'? '': '-50' }}" href="{{ route('scholarship.category', [$scholarship->id]) }}">
                        <i class="fas fa-money-check-alt"></i>
                        Category
                    </a>
                </li>
            @endcan
            @canany(['sendEmails', 'sendSMSes'], $scholarship)
                <li class="nav-item dropdown">
                    <a class="nav-link text-white{{ in_array($active, ['send-sms', 'send-email']) ? '': '-50' }} dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                        Send
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        @can('sendEmails', $scholarship)
                            <a class="dropdown-item {{ $active == 'send-email'? 'active': '' }}" href="{{ route('scholarship.send.email', [$scholarship->id]) }}">
                                Send Email
                            </a>
                        @endcan
                        @can('sendSMSes', $scholarship)
                            <a class="dropdown-item {{ $active == 'send-sms'? 'active': '' }}" href="{{ route('scholarship.send.sms', [$scholarship->id]) }}">
                                Send SMS
                            </a>
                        @endcan
                    </div>
                </li>
            @endcanany
        </ul>
    </nav>
    @endisset
</div>
