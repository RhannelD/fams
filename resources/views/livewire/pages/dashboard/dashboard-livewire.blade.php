<div>
    <script src="{{ asset('js/Chart.min.js') }}"></script>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-mid-bar border-bottom-0">
        <div class="navbar-brand ml-2 font-weight-bold mr-auto">
            Dashboard
        </div>
        <div class="d-flex mr-1">
            <select wire:model="scholarship_id" id="acad_year" class="form-control border-0 my-0">
                <option value="">All Scholarships</option>
                @foreach ($scholarships as $scholarship)
                    <option value="{{ $scholarship->id }}">
                        {{ $scholarship->scholarship }}
                    </option>
                @endforeach
            </select>
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
                        Ongoing Applications/Renewals
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
            <hr class="my-1">
        </div>
    </div>

    <div wire:ignore class="row mb-3 mx-1" id="chart_div_2">
        <div class="col-12 col-md-7">
            <canvas id="scholars_by_scholarship" width="100" height="250"></canvas>
        </div>
        <div class="col-12 col-md-5">
            <canvas id="scholars_by_municipality" width="100" height="250"></canvas>
        </div>
        <div class="col-12">
            <hr class="my-1">
        </div>
    </div>

    <div wire:ignore class="row mb-3 mx-1" id="scholars_by_course_div">
        <div class="col-12">
            <canvas id="scholars_by_course" width="100" height="400"></canvas>
        </div>
        <div class="col-12">
            <hr class="my-1">
        </div>
    </div>

    <script>
        var barColors = [
            "#00aba9",
            "#b91d47",
            '#494949',
            '#C2F1DB',
            '#496076',
            '#AE557F',
            '#132664',
            '#32A350',
            '#492B4C',
            '#F49E12',
        ];

        var scholarship_scholars_trend = null;
        var scholars_by_municipality = null;
        var scholars_by_course = null;
        var scholars_by_scholarship = null;
        
        window.addEventListener('scholarship_scholars_trend', event => { 
            if ( scholarship_scholars_trend != null ) {
                scholarship_scholars_trend.destroy();
            }

            var datasets = [];

            for (var key in event.detail.data) {
                dataset = {
                    label: event.detail.data[key]['scholarship'],
                    fill: false,
                    data: event.detail.data[key]['counts'],
                    borderColor: event.detail.data[key]['color'],
                    backgroundColor: event.detail.data[key]['color'],
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
                        text: "Approved vs Denied"
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    legend: {
                        display: true
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
                    label: event.detail.data[key]['scholarship'],
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
                        text: 'Number of Scholar on every Municipality'
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
        
        window.addEventListener('scholars_by_course', event => { 
            if ( scholars_by_course != null ) {
                scholars_by_course.destroy();
            }

            var all_color = barColors;

            while ( event.detail.data.length > all_color.length ) {
                all_color = all_color.concat(barColors);
            }

            scholars_by_course = new Chart("scholars_by_course", {
                type: 'horizontalBar',
                data: {
                    labels: event.detail.label,
                    datasets: [{
                        label: 'Course',
                        data: event.detail.data,
                        backgroundColor: all_color,
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    indexAxis: 'y',
                    title: {
                        display: true,
                        text: "Number of Scholar on every Course"
                    },
                    legend: {
                        display: false
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        },
                        xAxes: [{
                                ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                },
            });
        });

        window.addEventListener('scholars_by_scholarship', event => { 
            if ( scholars_by_scholarship != null ) {
                scholars_by_scholarship.destroy();
            }

            scholars_by_scholarship = new Chart("scholars_by_scholarship", {
                type: "horizontalBar",
                data: {
                labels: event.detail.label,
                datasets: [{
                    backgroundColor: event.detail.color,
                    data: event.detail.data,
                }]
                },  
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    indexAxis: 'y',
                    title: {
                        display: true,
                        text: "Scholars count Per Scholarship"
                    },
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                                ticks: {
                                beginAtZero: true
                            }
                        }],
                    }
                },
            });
        });

        $(document).ready(function(){
            window.livewire.emit('scholarship_scholars_trend');
            window.livewire.emit('scholars_by_scholarship');
            window.livewire.emit('scholars_by_course');
            window.livewire.emit('scholars_by_municipality');
        });
    </script>

</div>
