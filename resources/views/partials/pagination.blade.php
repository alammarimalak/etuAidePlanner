@if ($paginator->hasPages())
    <div class="pagination-shell">
        <p class="pagination-summary">
            Showing {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }} of {{ $paginator->total() }}
        </p>

        <nav class="pagination-nav" role="navigation" aria-label="Pagination">
            @if ($paginator->onFirstPage())
                <span class="pagination-link is-disabled" aria-disabled="true">Previous</span>
            @else
                <a class="pagination-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="pagination-separator" aria-hidden="true">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pagination-link is-active" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="pagination-link" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="pagination-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
            @else
                <span class="pagination-link is-disabled" aria-disabled="true">Next</span>
            @endif
        </nav>
    </div>
@endif
