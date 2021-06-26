<div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="bg-light border-right" id="sidebar-wrapper">
        <div class="sidebar-heading" style="font-size: 18px;">
            <i class="fas fa-user-graduate"></i>

            <span class="account_info_name">
                {{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}
            </span>

            <br>
            <button class="btn btn-sm btn-danger sign_out" wire:click="signout()">
              <i class="fas fa-sign-out"></i>
              Signout
            </button>
        </div>

        <div class="list-group list-group-flush">  
            <a class="list-group-item list-group-item-action bg-light tabs" wire:click="change_tab('dashboard')">
                <i class="fas fa-chart-line"></i>
                Dashboard
            </a> 

            <a class="list-group-item list-group-item-action bg-light tabs" wire:click="change_tab('scholarship')">
                <i class="fas fa-user-graduate"></i>
                Scholarships
            </a>

            <a class="list-group-item list-group-item-action bg-light tabs" wire:click="change_tab('scholar')">
                <i class="fas fa-user-graduate"></i>
                Scholars
            </a>

            <a class="list-group-item list-group-item-action bg-light tabs" id="tab_report">
                <i class="fas fa-print"></i>
                Report
            </a>
            
            <a class="list-group-item list-group-item-action bg-light tabs" id="tab_request">
                <i class="fas fa-paper-plane"></i>
                Request
            </a>
            
            <a class="list-group-item list-group-item-action bg-light tabs" id="tab_proposal">
                <i class="fas fa-file-alt"></i>
                Proposal Slip
            </a>
                
            <a class="list-group-item list-group-item-action bg-light tabs" id="tab_account">
                <i class="fad fa-cogs"></i>
                Account
            </a>
        </div>
    </div>
    <!-- /#sidebar-wrapper -->


    <!-- Page Content -->
    <div id="page-content-wrapper">

        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom d-flex align-content-center">
            <div class="float-left">
                <button class="btn" id="menu-toggle">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="offset-md-1 col-md-8 offset-0 col-10 container-fluid">
                <div class="icons ml-auto mr-2">
                    {{-- <img class="top-icon top-left-icon" src="../img/icon/BSU_icon.png"> --}}
                </div>

                <div>
                    <div class="top-title title-batstateu">BATANGAS STATE UNIVERSITY</div>
                </div>

                <div class="icons ml-2 mr-auto">
                   
                </div>
            </div>
        </nav>

        <div class="container-fluid" id="mainpanel">
            @switch($page)
                @case('dashboard')
                    <livewire:dashboard />
                    @break
                @case('scholarship')
                        <livewire:scholarship />
                        @break
                @case('scholar')
                    <livewire:scholar />
                    @break
            @endswitch
        </div>

    </div>

</div>