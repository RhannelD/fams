<div>
    @isset($scholarship)
    <div class="row mt-1 p-1">
        <div class="card col-12 bg-secondary text-white border-secondary">
            <h2 class="m-2 row">
                <strong class="my-auto">
                    {{ $scholarship->scholarship }}
                </strong>
                
                <div class="mr-1 ml-auto">
                    <a class="btn btn-light"
                        href="{{ route('scholarship.home', [$scholarship->id]) }}">
                        <i class="fas fa-newspaper"></i>
                        <strong>Home</strong>
                    </a>
                    <a class="btn btn-light"
                        href="{{ route('scholarship.scholar', [$scholarship->id]) }}">
                        <i class="fas fa-file-alt"></i>
                        <strong>Scholars</strong>
                    </a>
                    <a class="btn btn-light"
                        href="{{ route('scholarship.officer', [$scholarship->id]) }}">
                        <i class="fas fa-file-alt"></i>
                        <strong>Officers</strong>
                    </a>
                    @if (Auth::user()->usertype != 'scholar')
                        <a class="btn btn-light"
                            href="{{ route('scholarship.requirement', [$scholarship->id]) }}">
                            <i class="fas fa-file-alt"></i>
                            <strong>Requirements</strong>
                        </a>
                    @endif
                    <a class="btn btn-light"
                        href="{{ route('scholarship.category', [$scholarship->id]) }}">
                        <i class="fas fa-money-check-alt"></i>
                        <strong>Category</strong>
                    </a>
                </div>
            </h2>
        </div>
    </div>
    @endisset
</div>
