@if ($paginator->hasPages())
<nav>
    <ul class="pagination">
        {{-- 前のページへのリンク --}}
        @if ($paginator->onFirstPage())
        <li class="page-item disabled" aria-disabled="true">
            <span class="page-link">
                <
                    </span>
        </li>
        @else
        <li class="page-item">
            <a href="{{ $paginator->previousPageUrl() }}" class="page-link" rel="prev">
                <
                    </a>
        </li>
        @endif

        @php
        $start = 1;
        $end = 10;
        $lastPage = $paginator->lastPage();
        $currentPage = $paginator->currentPage();
        $secondLastPage = $lastPage - 1;
        @endphp

        {{-- ページ番号の表示 --}}
        @for ($i = $start; $i <= min($end, $lastPage); $i++)
            @if ($i==$currentPage)
            <li class="page-item active">
            <span class="page-link">{{ $i }}</span>
            </li>
            @else
            <li class="page-item">
                <a href="{{ $paginator->url($i) }}" class="page-link">{{ $i }}</a>
            </li>
            @endif
            @endfor

            {{-- 省略記号と最後の2ページ --}}
            @if ($lastPage > 10)
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link">...</span>
            </li>
            @if ($lastPage > $end)
            <li class="page-item">
                <a href="{{ $paginator->url($secondLastPage) }}" class="page-link">{{ $secondLastPage }}</a>
            </li>
            <li class="page-item">
                <a href="{{ $paginator->url($lastPage) }}" class="page-link">{{ $lastPage }}</a>
            </li>
            @endif
            @endif

            {{-- 次のページへのリンク --}}
            @if ($paginator->hasMorePages())
            <li class="page-item">
                <a href="{{ $paginator->nextPageUrl() }}" class="page-link" rel="next">></a>
            </li>
            @else
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link">>

                </span>
            </li>
            @endif
    </ul>
</nav>
@endif