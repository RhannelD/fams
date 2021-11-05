@extends('layouts.app')

@section('styles')
    @livewireStyles 
@endsection


@section('contents')

<div class="d-flex" id="wrapper">
@if ( Auth::check() )

    <!-- Sidebar -->
    <div class="bg-dark-grey-2" id="sidebar-wrapper">
        <div class="sidebar-heading" style="font-size: 26px;">
            <img src="{{ asset('img/scholarship-icon.png') }}" alt="" height="80px" class="mx-auto d-block mb-2">
        </div>

        <div class="list-group list-group-flush">  
            @if (Auth::user()->is_admin() || Auth::user()->is_officer())
                <a class="list-group-item list-group-item-action bg-dark-grey-2 hover-bg-white text-white border-white tabs" href="{{ route('dashboard') }}">
                    <i class="fas fa-chart-line"></i>
                    Dashboard
                </a> 
            @endif

            <a class="list-group-item list-group-item-action bg-dark-grey-2 hover-bg-white text-white border-white tabs" 
                @if (Auth::user()->is_scholar())
                    href="{{ route('scholar.scholarship') }}"
                @else
                    href="{{ route('scholarship') }}"
                @endif
                >
                <i class="fas fa-file-invoice-dollar"></i>
                Scholarship
            </a>

            @if ( Auth::user()->is_scholar() )
                <a class="list-group-item list-group-item-action bg-dark-grey-2 hover-bg-white text-white border-white tabs" href="{{ route('requirement.reponses.list') }}">
                    <i class="fas fa-file-upload"></i>
                    Response
                </a> 
            @endif

            <a class="list-group-item list-group-item-action bg-dark-grey-2 hover-bg-white text-white border-white tabs" 
                @if ( Auth::user()->is_admin() )
                    href="{{ route('officer') }}"   
                @else
                    href="{{ route('scholarship.officer') }}"   
                @endif
                >
                <i class="fas fa-address-card"></i>
                Officer
            </a>

            @if ( Auth::user()->is_admin() )
                <a class="list-group-item list-group-item-action bg-dark-grey-2 hover-bg-white text-white border-white tabs" href="{{ route('scholar') }}">
                    <i class="fas fa-user-graduate"></i>
                    Scholar
                </a>
            @endif

            @if ( Auth::user()->is_scholar() )
                <hr class="my-2">
                <strong class="ml-3 text-light">
                    Scholarship
                </strong>
                @livewire('add-ins.navbar-scholarship-livewire', key('sidebar-scholarship-'.time().$scholarship->id))
            @endif
        </div>
    </div>
    <!-- /#sidebar-wrapper -->


    <!-- Page Content -->
    <div id="page-content-wrapper">
        @yield('content')
    </div>
    
    
@endif
</div>

@endsection


@section('scripts')
    @livewireScripts
@endsection