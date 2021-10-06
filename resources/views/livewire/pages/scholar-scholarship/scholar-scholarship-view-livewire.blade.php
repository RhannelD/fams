<div>
    <ul wire:ignore class="nav nav-tabs d-flex justify-content-end my-2" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="scholarship-tab" data-toggle="tab" href="#scholarship" role="tab" aria-controls="scholarship" aria-selected="true">Home</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="scholarship-find-tab" data-toggle="tab" href="#scholarship-find" role="tab" aria-controls="scholarship-find" aria-selected="false">Find Scholarship</a>
        </li>
    </ul>
    <div class="tab-content mt-3" id="pills-tabContent">
        <div wire:ignore.self class="tab-pane fade show active" id="scholarship" role="tabpanel" aria-labelledby="scholarship-tab">
            <div class="row">
                @forelse ($scholarships as $scholarship)
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="card mb-2">
                            <div class="card-header bg-dark text-white">
                                <h4 class="my-1">
                                    <a href="{{ route('scholarship.home', [$scholarship->id]) }}">
                                        <strong class="text-white">{{ $scholarship->scholarship }}</strong>
                                    </a>
                                </h4>
                            </div>
                            <div class="card-body">
                                <table>
                                    <tr>
                                        <td>Category:</td>
                                        <td>{{ $scholarship->categories[0]->category }}</td>
                                    </tr>
                                    <tr>
                                        <td>Amount:</td>
                                        <td>Php {{ $scholarship->categories[0]->amount }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                                You dont have any scholarships yet. You may find other scholarships.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        <div wire:ignore class="tab-pane fade" id="scholarship-find" role="tabpanel" aria-labelledby="scholarship-find-tab">
            @livewire('scholar-scholarship.scholar-scholarship-find-livewire', key('scholar-scholarship-find-livewire-'.time()))
        </div>
    </div>
</div>
