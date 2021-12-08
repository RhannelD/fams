<div>
    <script src="{{ asset('js/Chart.min.js') }}"></script>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-mid-bar border-bottom-0">
        <div class="navbar-brand ml-2 font-weight-bold">
            Dashboard
        </div>
        <button class="btn btn-outline-light ml-auto" wire:click='refresh_all'>
            <i class="fas fa-sync-alt" wire:target="refresh_all" wire:loading.class.add='fa-spin'></i>
            Refresh
        </button>
    </nav>

    <div class="row mb-3 mt-2 mx-1">
        <div class="col-12">
            <h4 class="font-weight-bold mb-0">
                <a data-toggle="collapse" href="#collapse_pending_responses" role="button" aria-expanded="false" aria-controls="collapse_pending_responses">
                    Pending Scholars' Responses
                    <i class="fas fa-caret-down"></i>
                </a>
            </h4>
            <hr class="mb-2 mt-1">
        </div>
    </div>
    
    <div class="collapse" id="collapse_pending_responses">
        <div class="container-fluid">
            <div class="row">
                @foreach ($pending_responses as $scholarship)
                    <div class="col-12 col-md-6 col-lg-4 my-1 px-2 d-flex flex-column">
                        <div class="card flex-grow-1">
                            <div class="card-header bg-secondary ">
                                <h5 class="font-weight-bold my-auto">
                                    <a href="{{ route('scholarship.home', [$scholarship->id]) }}" class="text-white">
                                        {{ $scholarship->scholarship }}
                                    </a>
                                </h5>
                            </div>
                            <ul class="list-group list-group-flush">
                                @foreach ($scholarship->requirements as $requirement)
                                    <li class="list-group-item py-2 d-flex">
                                        <a href="{{ route('scholarship.requirement.responses', [$requirement->id]) }}"
                                            class="text-dark text-nowrap text-truncate"
                                            >
                                            {{ $requirement->requirement }}
                                        </a>
                                        <span class="badge badge-primary my-auto mr-0 ml-auto">
                                            {{ $requirement->responses->whereNotNull('submit_at')->whereNull('approval')->count() }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
                <div class="col-12">
                    <hr class="my-1">
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3 mx-1">
        <div class="col-12 col-sm-6 col-md-3 my-md-0 my-2 px-2 d-flex flex-column">
            <div class="card card-body flex-grow-1  bg-primary text-white">
                <div class="d-flex">
                    <h4 class="mr-auto">
                        <a wire:click='refresh_all' >
                            <i class="fas fa-sync-alt" wire:target="refresh_all" wire:loading.class.add='fa-spin'></i>
                        </a>
                    </h4>
                    <h4 class="text-right">
                        {{ $drafts }}
                    </h4>
                </div>
                <h5 class="mb-0 mt-auto">
                    Draft Applications/Renewals
                </h5>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3 my-md-0 my-2 px-2 d-flex flex-column">
            <div class="card card-body flex-grow-1  bg-success text-white">
                <div class="d-flex">
                    <h4 class="mr-auto">
                        <a wire:click='refresh_all' >
                            <i class="fas fa-sync-alt" wire:target="refresh_all" wire:loading.class.add='fa-spin'></i>
                        </a>
                    </h4>
                    <h4 class="text-right">
                        {{ $pending_applications }}
                    </h4>
                </div>
                <h5 class="mb-0 mt-auto">
                    Pending Applications
                </h5>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3 my-md-0 my-2 px-2 d-flex flex-column">
            <div class="card card-body flex-grow-1 bg-info text-white">
                <div class="d-flex">
                    <h4 class="mr-auto">
                        <a wire:click='refresh_all' >
                            <i class="fas fa-sync-alt" wire:target="refresh_all" wire:loading.class.add='fa-spin'></i>
                        </a>
                    </h4>
                    <h4 class="text-right">
                        {{ $pending_renewals }}
                    </h4>
                </div>
                <h5 class="mb-0 mt-auto">
                    Pending Renewals
                </h5>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3 my-md-0 my-2 px-2 d-flex flex-column">
            <div class="card card-body flex-grow-1 bg-dark text-white">
                <div class="d-flex">
                    <h4 class="mr-auto">
                        <a wire:click='refresh_all' >
                            <i class="fas fa-sync-alt" wire:target="refresh_all" wire:loading.class.add='fa-spin'></i>
                        </a>
                    </h4>
                    <h4 class="text-right">
                        {{ $pending_all }}
                    </h4>
                </div>
                <h5 class="mb-0 mt-auto">
                    Total Pending Applications/Renewals
                </h5>
            </div>
        </div>
    </div>
    
    <div class="row mb-3 mx-1">
        <div class="col-12 col-md-8">
            <canvas id="responses_chart" width="100" height="300"></canvas>
        </div>
        <div class="col-12 col-md-4">
            <div class="card" style="max-height: 300px">
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

    <div class="row mb-3 mx-1">
        <div class="col-12">
            <canvas id="scholars_by_municipality" width="100" height="200"></canvas>
        </div>
        <div class="col-12">
            <hr class="my-1">
        </div>
    </div>

    <div class="row mb-3 mx-1">
        <div class="col-12">
            <canvas id="scholars_by_course" width="100" height="500"></canvas>
        </div>
        <div class="col-12">
            <hr class="my-1">
        </div>
    </div>

    <div class="row mb-3 mx-1">
        <div class="col-10 offset-1 offset-md-0 col-md-8 col-lg-6 col-xl-4">
            <canvas id="scholars_by_scholarship" width="100" height="100"></canvas>
        </div>
        <div class="col-10 offset-1 offset-md-0 col-md-4 col-lg-6 col-xl-8">
            <div class="row">
                <div class="col-10 offset-1 offset-md-0 col-md-12 col-lg-6 col-xl-4">
                    <canvas id="scholar" width="100" height="100"></canvas>
                </div>
                <div class="col-10 offset-1 offset-md-0 col-md-12 col-lg-6 col-xl-4">
                    <canvas id="scholarship" width="100" height="100"></canvas>
                </div>
                <div class="col-10 offset-1 offset-md-0 col-md-12 col-lg-6 col-xl-4">
                    <canvas id="scholars_by_gender" width="100" height="100"></canvas>
                </div>
            </div>
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
        
        window.addEventListener('responses_chart', event => { 
            new Chart("responses_chart", {
                type: 'line',
                data: {
                    labels: event.detail.label,
                    datasets: [{
                        label: 'Responses',
                        fill: false,
                        data: event.detail.data,
                        backgroundColor: '#00aba9',
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    title: {
                        display: true,
                        text: "Submitted Applications/Renewals every quarter"
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    legend: {
                        display: false
                    },
                    // elements: {
                    //     line: {
                    //         tension: 0, // disables bezier curves
                    //     }
                    // }
                },
            });
        });
        
        window.addEventListener('scholars_by_municipality', event => { 
            var all_color = barColors;

            while ( event.detail.data.length > all_color.length ) {
                all_color = all_color.concat(barColors);
            }

            new Chart("scholars_by_municipality", {
                type: 'horizontalBar',
                data: {
                    labels: event.detail.label,
                    datasets: [{
                        label: 'Muniipality',
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
        
        window.addEventListener('scholars_by_course', event => { 
            var all_color = barColors;

            while ( event.detail.data.length > all_color.length ) {
                all_color = all_color.concat(barColors);
            }

            new Chart("scholars_by_course", {
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
                        }
                    }
                },
            });
        });

        window.addEventListener('scholar_chart', event => { 
            new Chart("scholar", {
                type: "pie",
                data: {
                labels: event.detail.label,
                datasets: [{
                    backgroundColor: barColors,
                    data: event.detail.data
                }]
                },  
                options: {
                    title: {
                        display: true,
                        text: "Scholar count per acquired Scholarships"
                    }
                }
            });
        });

        window.addEventListener('scholarship_chart', event => { 
            new Chart("scholarship", {
                type: "pie",
                data: {
                labels: event.detail.label,
                datasets: [{
                    backgroundColor: barColors,
                    data: event.detail.data
                }]
                },  
                options: {
                    title: {
                        display: true,
                        text: "Scholarships number of Categories"
                    }
                }
            });
        });

        window.addEventListener('scholars_by_gender', event => { 
            new Chart("scholars_by_gender", {
                type: "pie",
                data: {
                labels: event.detail.label,
                datasets: [{
                    backgroundColor: barColors,
                    data: event.detail.data
                }]
                },  
                options: {
                    title: {
                        display: true,
                        text: "Scholars by Sex"
                    }
                }
            });
        });

        window.addEventListener('scholars_by_scholarship', event => { 
            new Chart("scholars_by_scholarship", {
                type: "pie",
                data: {
                labels: event.detail.label,
                datasets: [{
                    backgroundColor: barColors,
                    data: event.detail.data
                }]
                },  
                options: {
                    title: {
                        display: true,
                        text: "Scholars Per Scholarship"
                    }
                }
            });
        });

        $(document).ready(function(){
            window.livewire.emit('responses_chart');
            window.livewire.emit('scholar_chart');
            window.livewire.emit('scholarship_chart');
            window.livewire.emit('scholars_by_gender');
            window.livewire.emit('scholars_by_scholarship');
            window.livewire.emit('scholars_by_course');
            window.livewire.emit('scholars_by_municipality');
        });
    </script>

</div>
