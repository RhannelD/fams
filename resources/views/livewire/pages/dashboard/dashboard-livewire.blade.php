<div>
    <script src="{{ asset('js/Chart.min.js') }}"></script>
    <div class="row mt-1">
        <div class="col-5">
            <h2 class="text-bold">Dashboard</h2>
        </div>
        <div class="col-7">
            <button wire:click='refresh_all' class="btn btn-info float-right text-white">
                <i class="fas fa-sync-alt" wire:target="refresh_all" wire:loading.class.add='fa-spin'></i>
                Refresh
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-10 offset-1 offset-sm-0 col-sm-6 col-lg-4 col-xl-3">
            <canvas id="scholar" width="100" height="100"></canvas>
        </div>
        <div class="col-10 offset-1 offset-sm-0 col-sm-6 col-lg-4 col-xl-3">
            <canvas id="scholarship" width="100" height="100"></canvas>
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

        $(document).ready(function(){
            window.livewire.emit('scholar_chart');
            window.livewire.emit('scholarship_chart');
        });
    </script>

</div>
