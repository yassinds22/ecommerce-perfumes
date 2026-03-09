@if ($paginator->hasPages())
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <button class="pagination__btn disabled"><i class="fas fa-chevron-right"></i></button>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination__btn"><i class="fas fa-chevron-right"></i></a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="pagination__dots">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <button class="pagination__page active">{{ $page }}</button>
                    @else
                        <a href="{{ $url }}" class="pagination__page">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination__btn"><i class="fas fa-chevron-left"></i></a>
        @else
            <button class="pagination__btn disabled"><i class="fas fa-chevron-left"></i></button>
        @endif
    </div>
@endif
