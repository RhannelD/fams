<div>
    @foreach ($scholarships as $scholarship)
        <a class="list-group-item list-group-item-action bg-light tabs border-top-0 border-right-0 
            @if ($loop->last)
                border-bottom-0 
            @endif 
            "
            href="{{ route('scholarship.home', [$scholarship->id]) }}">
            <i class="fas fa-money-check"></i>
            {{ $scholarship->scholarship }}
        </a>
    @endforeach
</div>
