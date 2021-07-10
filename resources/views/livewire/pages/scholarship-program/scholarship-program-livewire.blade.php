<div>
    <div class="row mt-1 p-1">
        <div class="card col-12 bg-secondary text-white border-secondary">
            <h2 class="m-2 row">
                <strong class="my-auto">
                    {{ $scholarship->scholarship }}
                </strong>
                
                <div class="mr-1 ml-auto">
                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item mr-1" role="presentation">
                            <a wire:click="changetab('home')" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" 
                                @if ($tab == 'home')
                                    class="btn btn-light active"
                                    aria-selected="true"
                                @else
                                    class="btn btn-light"
                                    aria-selected="false"
                                @endif
                                >
                                <strong>Home</strong>
                            </a>
                        </li>
                        <li class="nav-item mr-1" role="presentation">
                            <a wire:click="changetab('scholar')" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" 
                                @if ($tab == 'scholar')
                                    class="btn btn-light active"
                                    aria-selected="true"
                                @else
                                    class="btn btn-light"
                                    aria-selected="false"
                                @endif
                                >
                                <strong>Scholars</strong>
                            </a>
                        </li>
                        <li class="nav-item mr-1" role="presentation">
                            <a wire:click="changetab('officer')" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact"
                                @if ($tab == 'officer')
                                    class="btn btn-light active"
                                    aria-selected="true"
                                @else
                                    class="btn btn-light"
                                    aria-selected="false"
                                @endif
                                >
                                <strong>Officers</strong>
                            </a>
                        </li>
                    </ul>
                </div>
            </h2>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="tab-content" id="pills-tabContent">
                <div id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab"
                    @if ($tab == 'home')
                        class="tab-pane fade show active" 
                    @else
                        class="tab-pane fade" 
                    @endif
                    >
                    @livewire('scholarship-page-livewire', key('page-tabs-'.time().$scholarship->id))
                </div>
                <div id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
                    @if ($tab == 'scholar')
                        class="tab-pane fade show active" 
                    @else
                        class="tab-pane fade" 
                    @endif
                    >
                    @livewire('scholarship-scholar-livewire', [$scholarship->id], key('scholar-tabs-'.time().$scholarship->id))
                </div>
                <div id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab"
                    @if ($tab == 'officer')
                        class="tab-pane fade show active" 
                    @else
                        class="tab-pane fade" 
                    @endif
                    >
                    @livewire('scholarship-officer-livewire', [$scholarship->id], key('officer-tabs-'.time().$scholarship->id))
                </div>
            </div>
        </div>
    </div>
</div>
