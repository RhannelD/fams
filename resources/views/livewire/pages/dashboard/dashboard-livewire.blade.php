<div>
    <script src="{{ asset('js/Chart.min.js') }}"></script>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-mid-bar border-bottom-0">
        <div class="navbar-brand ml-2 font-weight-bold mr-auto">
            Dashboard
        </div>
        <div class="d-flex mr-1">
            @include('livewire.pages.dashboard.dashboard-filter')
        </div>
        <button class="btn btn-outline-light mr-1" wire:click='refresh_all'>
            <i class="fas fa-sync-alt" wire:target="refresh_all" wire:loading.class.add='fa-spin'></i>
            Refresh
        </button>
    </nav>

    <div class="row mb-3 mx-1 mt-2">
        <div class="col-12 col-sm-6 col-md-3 my-md-0 my-2 px-2 d-flex flex-column">
            <div class="card card-body flex-grow-1  bg-primary text-white p-3">
                <div class="d-flex">
                    <h5 class="mr-auto">
                        <a wire:click='refresh_all' >
                            <i class="fas fa-sync-alt" wire:target="refresh_all" wire:loading.class.add='fa-spin'></i>
                        </a>
                    </h5>
                    <h5 class="text-right">
                        {{ $drafts }}
                    </h5>
                </div>
                <h6 class="mb-0 mt-auto">
                    Draft Applications/Renewals
                </h6>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3 my-md-0 my-2 px-2 d-flex flex-column">
            <div class="card card-body flex-grow-1  bg-success text-white p-3">
                <div class="d-flex">
                    <h5 class="mr-auto">
                        <a wire:click='refresh_all' >
                            <i class="fas fa-sync-alt" wire:target="refresh_all" wire:loading.class.add='fa-spin'></i>
                        </a>
                    </h5>
                    <h5 class="text-right">
                        {{ $pending_applications }}
                    </h5>
                </div>
                <h6 class="mb-0 mt-auto">
                    Pending Applications
                </h6>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3 my-md-0 my-2 px-2 d-flex flex-column">
            <div class="card card-body flex-grow-1 bg-info text-white p-3">
                <div class="d-flex">
                    <h5 class="mr-auto">
                        <a wire:click='refresh_all' >
                            <i class="fas fa-sync-alt" wire:target="refresh_all" wire:loading.class.add='fa-spin'></i>
                        </a>
                    </h5>
                    <h5 class="text-right">
                        {{ $pending_renewals }}
                    </h5>
                </div>
                <h6 class="mb-0 mt-auto">
                    Pending Renewals
                </h6>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3 my-md-0 my-2 px-2 d-flex flex-column">
            <div class="card card-body flex-grow-1 bg-dark text-white p-3">
                <div class="d-flex">
                    <h5 class="mr-auto">
                        <a wire:click='refresh_all' >
                            <i class="fas fa-sync-alt" wire:target="refresh_all" wire:loading.class.add='fa-spin'></i>
                        </a>
                    </h5>
                    <h5 class="text-right">
                        {{ $pending_all }}
                    </h5>
                </div>
                <h6 class="mb-0 mt-auto">
                    Total Pending Applications/Renewals
                </h6>
            </div>
        </div>
    </div>
    
    <div class="row mb-3 mx-1">
        <div wire:ignore class="col-12 col-md-8" id="scholarship_scholars_trend_div">
            <canvas id="scholarship_scholars_trend" width="100" height="350"></canvas>
        </div>
        <div class="col-12 col-md-4 d-flex flex-column">
            <div class="card border-secondary flex-grow-1 flex-shrink-1" style="max-height: 350px">
                <div class="card-header bg-secondary py-2">
                    <h5 class="my-auto text-white">
                        Important Dates to remember
                    </h5>
                </div>
                <div class="card-body p-2  overflow-auto">
                    <table class="table table-sm table-borderless">
                        <tbody>
                            @foreach ($ongoing_requirements as $ongoing_requirement)
                                <tr>
                                    <td>
                                        @if ( $ongoing_requirement->enable )
                                            <div class="my-auto text-secondary">
                                                No Due
                                            </div>
                                        @else
                                            <div class="d-flex">
                                                <h4 class="my-0 py-0">
                                                    {{ \Carbon\Carbon::parse($ongoing_requirement->end_at)->format('d') }}
                                                </h4>
                                                <h6 class="my-0 text-secondary text-10px">
                                                    {{ \Carbon\Carbon::parse($ongoing_requirement->end_at)->format('M') }}
                                                    <br>
                                                    {{ \Carbon\Carbon::parse($ongoing_requirement->end_at)->format('Y') }}
                                                </h6>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="">
                                            <a href="{{ route('scholarship.requirement.responses', [$ongoing_requirement->id]) }}" class="text-decoration-none text-dark">
                                                {{ $ongoing_requirement->requirement }}
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12">
            <hr class="my-3">
        </div>
    </div>

    <div wire:ignore class="row mb-3 mx-1" id="chart_div_2">
        <div class="col-12 col-md-8">
            <canvas id="response_approve_denied" width="100" height="300"></canvas>
        </div>
        <div class="col-12 col-md-4">
            <canvas id="scholars_by_municipality" width="100" height="300"></canvas>
        </div>
        <div class="col-12">
            <hr class="my-2">
        </div>
    </div>

    <script>
        var scholarship_scholars_trend = null;
        var scholars_by_municipality = null;
        var response_approve_denied = null;
        
        window.addEventListener('scholarship_scholars_trend', event => { 
            if ( scholarship_scholars_trend != null ) {
                scholarship_scholars_trend.destroy();
            }

            var datasets = [];

            for (var key in event.detail.data) {
                dataset = {
                    label: event.detail.data[key]['label'],
                    fill: false,
                    data: event.detail.data[key]['counts'],
                    borderColor: event.detail.data[key]['color'],
                    backgroundColor: event.detail.data[key]['color'],
                    borderWidth: 2,
                };

                datasets.push(dataset);
            }

            scholarship_scholars_trend = new Chart("scholarship_scholars_trend", {
                type: 'line',
                data: {
                    labels: event.detail.label,
                    datasets: datasets,
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    title: {
                        display: true,
                        text: event.detail.title,
                    },
                    scales: {
                    },
                    legend: {
                        display: false
                    },
                },
            });
        });
        
        window.addEventListener('scholars_by_municipality', event => { 
            if ( scholars_by_municipality != null ) {
                scholars_by_municipality.destroy();
            }

            var datasets = [];

            for (var key in event.detail.data) {
                dataset = {
                    label: event.detail.data[key]['label'],
                    data: event.detail.data[key]['data'],
                    backgroundColor: event.detail.data[key]['color'],
                };

                datasets.push(dataset);
            }

            scholars_by_municipality = new Chart("scholars_by_municipality", {
                type: 'horizontalBar',
                data: {
                    labels: event.detail.label,
                    datasets: datasets,
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    indexAxis: 'y',
                    title: {
                        display: true,
                        text: 'Number of Scholar in each Municipality'
                    },
                    scales: {
                        xAxes: [{
                            stacked: true
                        }],
                        yAxes: [{
                            stacked: true
                        }]
                    },
                    legend: {
                        display: false
                    },
                },
            });
        });
        
        window.addEventListener('response_approve_denied', event => { 
            if ( response_approve_denied != null ) {
                response_approve_denied.destroy();
            }

            response_approve_denied = new Chart("response_approve_denied", {
                type: 'bar',
                data: {
                    labels: event.detail.label,
                    datasets: [
                        {
                            label: 'Approved',
                            data: event.detail.data['approved']['counts'],
                            backgroundColor: '#61C97D',
                        },
                        {
                            label: 'Denied',
                            data: event.detail.data['denied']['counts'],
                            backgroundColor: '#FFAAAA',
                        },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    indexAxis: 'y',
                    title: {
                        display: true,
                        text: event.detail.title,
                    },
                    legend: {
                        display: true
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true,
                            }
                        }],
                    }
                },
            });
        });

        $(document).ready(function(){
            window.livewire.emit('scholarship_scholars_trend');
            window.livewire.emit('response_approve_denied');
            window.livewire.emit('scholars_by_municipality');
        });
    </script>

</div>
