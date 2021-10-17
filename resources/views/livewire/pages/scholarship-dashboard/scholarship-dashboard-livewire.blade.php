<div>
    <script src="{{ asset('js/Chart.min.js') }}"></script>
    <script src="https://www.chartjs.org/samples/2.9.4/utils.js"></script>
    @livewire('add-ins.scholarship-program-livewire', [$scholarship_id], key('page-tabs-'.time().$scholarship_id))

    <hr class="mb-2">
    <div class="row mt-1 mx-2">
        <div class="col-12 d-flex flex-row-reverse">
            <button wire:click='refresh_all' class="btn btn-success float-right text-white">
                <i class="fas fa-sync-alt" wire:target="refresh_all" wire:loading.class.add='fa-spin'></i>
                Refresh
            </button>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <canvas id="responses_chart" width="100" height="300"></canvas>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-10 offset-1 offset-md-0 col-md-12 col-lg-6 col-xl-4">
            <canvas id="scholars" width="100" height="100"></canvas>
        </div>
        <div class="col-10 offset-1 offset-md-0 col-md-12 col-lg-6 col-xl-4">
            <canvas id="scholars_by_gender" width="100" height="100"></canvas>
        </div>
        <div class="col-10 offset-1 offset-md-0 col-md-12 col-lg-6 col-xl-4">
            <canvas id="scholars_scholarship_count" width="100" height="100"></canvas>
        </div>
    </div>

    <script>
        var barColors = [
            "#00aba9",
            "#b91d47",
            '#494949',
            '#C2F1DB',
            '#496076'
        ];
        
        window.addEventListener('responses_chart', event => { 
            var datasets = [];
            var color_index = 0;

            $.each(event.detail.data, function( key, value ) {
                datasets.push({
                    label: key,
                    data: event.detail.data[key],
                    borderColor: barColors[color_index],
                    fill: false,
                });
                color_index += 1;
            });

            new Chart("responses_chart", {
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
                        text: "Scholar responses every month for renewal."
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    stacked: false,
                },
            });
        });

        window.addEventListener('scholar_count_chart', event => { 
            new Chart("scholars", {
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
                        text: "Scholar Count"
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
                        text: "Scholars by Gender"
                    }
                }
            });
        });

        window.addEventListener('scholars_scholarship_count', event => { 
            new Chart("scholars_scholarship_count", {
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
                        text: "Number of Scholarship of Scholars"
                    }
                }
            });
        });

        $(document).ready(function(){
            window.livewire.emit('responses_chart');
            window.livewire.emit('scholar_count_chart');
            window.livewire.emit('scholars_by_gender');
            window.livewire.emit('scholars_scholarship_count');
        });
    </script>

</div>