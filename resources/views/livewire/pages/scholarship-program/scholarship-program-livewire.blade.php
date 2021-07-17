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
                                <i class="fas fa-newspaper"></i>
                                <strong>Home</strong>
                            </a>
                        </li>
                        <li class="nav-item mr-1" role="presentation">
                            <a wire:click="changetab('scholar')" id="pills-scholar-tab" data-toggle="pill" href="#pills-scholar" role="tab" aria-controls="pills-scholar" 
                                @if ($tab == 'scholar')
                                    class="btn btn-light active"
                                    aria-selected="true"
                                @else
                                    class="btn btn-light"
                                    aria-selected="false"
                                @endif
                                >
                                <i class="fas fa-user-graduate"></i>
                                <strong>Scholars</strong>
                            </a>
                        </li>
                        <li class="nav-item mr-1" role="presentation">
                            <a wire:click="changetab('officer')" id="pills-officer-tab" data-toggle="pill" href="#pills-officer" role="tab" aria-controls="pills-officer"
                                @if ($tab == 'officer')
                                    class="btn btn-light active"
                                    aria-selected="true"
                                @else
                                    class="btn btn-light"
                                    aria-selected="false"
                                @endif
                                >
                                <i class="fas fa-address-card"></i>
                                <strong>Officers</strong>
                            </a>
                        </li>
                        @if (Auth::user()->usertype != 'scholar')
                            <li class="nav-item mr-1" role="presentation">
                                <a wire:click="changetab('requirement')" id="pills-requirement-tab" data-toggle="pill" href="#requirement-contact" role="tab" aria-controls="requirement-contact"
                                    @if ($tab == 'requirement')
                                        class="btn btn-light active"
                                        aria-selected="true"
                                    @else
                                        class="btn btn-light"
                                        aria-selected="false"
                                    @endif
                                    >
                                    <i class="fas fa-file-alt"></i>
                                    <strong>Requirements</strong>
                                </a>
                            </li>
                        @endif
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
                    @livewire('scholarship-page-livewire', [$scholarship->id], key('page-tabs-'.time().$scholarship->id))
                </div>
                <div id="pills-scholar" role="tabpanel" aria-labelledby="pills-scholar-tab"
                    @if ($tab == 'scholar')
                        class="tab-pane fade show active" 
                    @else
                        class="tab-pane fade" 
                    @endif
                    >
                    @livewire('scholarship-scholar-livewire', [$scholarship->id], key('scholar-tabs-'.time().$scholarship->id))
                </div>
                <div id="pills-officer" role="tabpanel" aria-labelledby="pills-officer-tab"
                    @if ($tab == 'officer')
                        class="tab-pane fade show active" 
                    @else
                        class="tab-pane fade" 
                    @endif
                    >
                    @livewire('scholarship-officer-livewire', [$scholarship->id], key('officer-tabs-'.time().$scholarship->id))
                </div>
                @if (Auth::user()->usertype != 'scholar')
                    <div id="pills-requirement" role="tabpanel" aria-labelledby="pills-requirement-tab"
                        @if ($tab == 'requirement' && empty($requirement_id))
                            class="tab-pane fade show active" 
                        @else
                            class="tab-pane fade" 
                        @endif
                        >
                        @livewire('scholarship-requirement-livewire', [$scholarship->id], key('requirement-tabs-'.time().$scholarship->id))
                    </div>
                    <div id="pills-requirement" role="tabpanel" aria-labelledby="pills-requirement-tab"
                        @if ($tab == 'requirement' && !empty($requirement_id))
                            class="tab-pane fade show active" 
                        @else
                            class="tab-pane fade" 
                        @endif
                        >
                        @if (!empty($requirement_id))
                            @livewire('scholarship-requirement-open-livewire', [$requirement_id], key('requirement-open-tabs-'.time().$requirement_id))
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
