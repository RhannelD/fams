<div>
    @forelse ($scholarships as $scholarship)
        <a class="list-group-item list-group-item-action bg-dark-grey-2 hover-bg-white text-white border-white tabs border-0"
            href="{{ route('scholarship.home', [$scholarship->id]) }}">
            <i class="fas fa-money-check"></i>
            {{ $scholarship->scholarship }}
        </a>
    @empty
        <a class="list-group-item list-group-item-action bg-dark-grey-2 hover-bg-white text-white tabs border-0">
            None
        </a>
    @endforelse
</div>
