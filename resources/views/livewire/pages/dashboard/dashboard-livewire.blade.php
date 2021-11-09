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

    <div class="row mb-3 mx-1">
        <div class="col-12">
            <canvas id="responses_chart" width="100" height="300"></canvas>
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
                type: 'bar',
                data: {
                    labels: event.detail.label,
                    datasets: [{
                        label: 'Responses',
                        data: event.detail.data,
                        backgroundColor: barColors,
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    title: {
                        display: true,
                        text: "Scholar responses every quarter"
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
        });
    </script>

</div>
