<div>
    <script src="{{ asset('js/Chart.min.js') }}"></script>
    <div class="row mt-1 mx-2">
        <div class="card col-12 bg-secondary text-white border-secondary">
            <h2 class="m-2 row">
                <strong class="my-auto">
                    Dashboard
                </strong>
                
                <div class="mr-1 ml-auto">
                    <button wire:click='refresh_all' class="btn btn-success float-right text-white">
                        <i class="fas fa-sync-alt" wire:target="refresh_all" wire:loading.class.add='fa-spin'></i>
                        Refresh
                    </button>
                </div>
            </h2>
        </div>
    </div>
    <div class="row mb-3">
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
            window.livewire.emit('scholar_chart');
            window.livewire.emit('scholarship_chart');
            window.livewire.emit('scholars_by_gender');
            window.livewire.emit('scholars_by_scholarship');
        });
    </script>

</div>
