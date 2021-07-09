<div>
    <div class="row mt-1 p-1">
        <div class="card col-12 bg-secondary text-white border-secondary">
            <h2 class="m-2 row">
                <strong class="my-auto">
                    {{ $scholarship->scholarship }}
                </strong>
                
                <div class="mr-1 ml-auto">
                    <button wire:click="changetab('scholar')" class="btn btn-light">
                        <strong>Scholars</strong>
                    </button>
                    <button wire:click="changetab('officer')" class="btn btn-light">
                        <strong>Officers</strong>
                    </button>
                </div>
            </h2>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @switch($tab)
                @case('scholar')   
                    @livewire('scholarship-scholar-livewire', [$scholarship->id])
                    @break
            
                @default
                    @break
            @endswitch
        </div>
    </div>
</div>
