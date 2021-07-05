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
            <a class="list-group-item list-group-item-action bg-light tabs" href="{{ route('dashboard') }}">
                <i class="fas fa-chart-line"></i>
                Dashboard
            </a> 

            <a class="list-group-item list-group-item-action bg-light tabs" href="{{ route('scholarhip') }}">
                <i class="fas fa-user-graduate"></i>
                Scholarships
            </a>

            <a class="list-group-item list-group-item-action bg-light tabs" href="{{ route('officer') }}">
                <i class="fas fa-user-graduate"></i>
                Officers
            </a>
            <a class="list-group-item list-group-item-action bg-light tabs" href="{{ route('scholar') }}">
                <i class="fas fa-user-graduate"></i>
                Scholars
            </a>
        </div>
    </div>
    <!-- /#sidebar-wrapper -->


    <!-- Page Content -->
    <div id="page-content-wrapper">

        {{-- <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom d-flex align-content-center">
            <div class="float-left">
                <button class="btn" id="menu-toggle">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            
            <a class="navbar-brand ml-3"><strong>FAMS</strong></a>

            <div class="collapse navbar-collapse" id="navbarSuapportedContent-4">
			</div>
        </nav> --}}
        
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
							<a class="dropdown-item" href="#">
                                <i class="fas fa-user-circle"></i>
                                My account
                            </a>
							<livewire:logout-livewire />
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