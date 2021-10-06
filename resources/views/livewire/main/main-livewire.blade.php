@extends('layouts.app')

@section('styles')
    @livewireStyles 
@endsection


@section('contents')

<div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="bg-light border-right" id="sidebar-wrapper">
        <div class="sidebar-heading" style="font-size: 26px;">
            <img src="{{ asset('img/scholarship-icon.png') }}" alt="" height="80px" class="mx-auto d-block mb-2">
        </div>

        <div class="list-group list-group-flush">  
            @if (in_array(Auth::user()->usertype, array('officer', 'admin')))
                <a class="list-group-item list-group-item-action bg-light tabs" href="{{ route('dashboard') }}">
                    <i class="fas fa-chart-line"></i>
                    Dashboard
                </a> 
            @endif

            <a class="list-group-item list-group-item-action bg-light tabs" 
                @if (Auth::user()->is_scholar())
                    href="{{ route('scholar.scholarship') }}"
                @else
                    href="{{ route('scholarship') }}"
                @endif
                >
                <i class="fas fa-file-invoice-dollar"></i>
                Scholarships
            </a>

            @if (!Auth::check())
                <hr>
            @elseif (Auth::user()->usertype == 'admin')
                <a class="list-group-item list-group-item-action bg-light tabs" href="{{ route('officer') }}">
                    <i class="fas fa-address-card"></i>
                    Officers
                </a>
                <a class="list-group-item list-group-item-action bg-light tabs" href="{{ route('scholar') }}">
                    <i class="fas fa-user-graduate"></i>
                    Scholars
                </a>
            @elseif (in_array(Auth::user()->usertype, array('officer', 'scholar')))
                <hr class="my-2">
                <strong class="ml-3">
                    @if (Auth::user()->usertype == 'officer')
                        Managing
                    @else
                        Scholarship
                    @endif
                </strong>
                @livewire('add-ins.navbar-scholarship-livewire', key('sidebar-scholarship-'.time().$scholarship->id))
            @endif
        </div>
    </div>
    <!-- /#sidebar-wrapper -->


    <!-- Page Content -->
    <div id="page-content-wrapper">

        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <button class="btn btn-outline-secondary disabled" id="menu-toggle">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-brand ml-2 font-weight-bold">FAMS</div>

            <button type="" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse1">
                <i class="fas fa-ellipsis-v"></i>
            </button>
    
            <div class="collapse navbar-collapse" id="navbarCollapse1">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <strong>{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }} </strong>
                        </a>
						<div class="dropdown-menu dropdown-menu-right dropdown-cyan" aria-labelledby="navbarDropdownMenuLink-4">
							<a class="dropdown-item" href="{{ route('my.account') }}">
                                <i class="fas fa-user-circle"></i>
                                My account
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
                                </a>
                            @endif
                            @livewire('add-ins.logout-livewire')
						</div>
					</li>
				</ul>
            </div>
        </nav>

        <div class="container-fluid" id="mainpanel">
            
            @yield('content')
            
        </div>

    </div>

</div>

@endsection


@section('scripts')
    @livewireScripts
@endsection