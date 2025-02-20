@if ($paginator->hasPages())
    <nav class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="disabled">التالي</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-link">التالي</a>
        @endif

        {{-- Pagination Elements --}}
        <div class="pages">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="dots">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-link">السابق</a>
        @else
            <span class="disabled">السابق</span>
        @endif
    </nav>
@endif


<style>
    .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin-top: 20px;
}

.page-link {
    text-decoration: none;
    padding: 8px 12px;
    background: #6c7a89;
    color: white;
    border-radius: 5px;
    transition: background 0.3s ease-in-out;
}

.page-link:hover {
    background: #000033;
}

.disabled {
    padding: 8px 12px;
    background: #d6d6d6;
    color: #8a8a8a;
    border-radius: 5px;
    pointer-events: none;
}

.active {
    padding: 8px 12px;
    background: #6c7a89;
    color: white;
    border-radius: 5px;
}

.pages {
    display: flex;
    gap: 5px;
}

.dots {
    padding: 8px 12px;
    color: #555;
}

</style>