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
            <a class="list-group-item list-group-item-action bg-light tabs" href="{{ route('index') }}">
                <i class="fas fa-home"></i>
                Home
            </a>
        </div>
        <div>
            @livewire('user-chat.user-chat-list-livewire')
        </div>
    </div>
    <!-- /#sidebar-wrapper -->


    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100">
                <div class="col-12">
                    <div class="h-100 d-flex flex-column">
                        <div class="row {{-- justify-content-center --}} bg-primary">
                            <nav class="col-12 navbar navbar-expand-lg navbar-light bg-light border-bottom  {{-- justify-content-center --}}">
                                <button onclick="menu_toggle()" class="btn btn-outline-secondary disabled" id="menu-toggle">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <div class="navbar-brand ml-2 font-weight-bold">{{ config('app.name') }}</div>
                    
                                <button type="" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse1">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                        
                                <div class="collapse navbar-collapse" id="navbarCollapse1">
                                    <ul class="navbar-nav ml-auto">
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <strong>{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }} </strong>
                                                @if ( Auth::user()->get_invite_count() )
                                                    <span class="badge badge-danger">
                                                        {{ Auth::user()->get_invite_count() }}
                                                    </span>
                                                @endif
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-cyan" aria-labelledby="navbarDropdownMenuLink-4">
                                                <a class="dropdown-item" href="{{ route('my.account') }}">
                                                    <i class="fas fa-user-circle"></i>
                                                    My account
                                                </a>
                                                @if ( Auth::user()->is_scholar() )
                                                    <a class="dropdown-item" href="{{ route('invite.scholar') }}">
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
                        </div>

                        <div class="row justify-content-center flex-grow-1" id="mainpanel">
                            <div class="row container-fluid px-0 d-flex flex-column">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('scripts')
    @livewireScripts
@endsection
