@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" style="display: flex; justify-content: center; align-items: center; gap: 10px; padding: 20px 0;">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <button class="btn-prev" disabled style="opacity: 0.5; cursor: not-allowed; padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px;">
                Anterior
            </button>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn-prev" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px; text-decoration: none;">
                Anterior
            </a>
        @endif

        {{-- Page Numbers --}}
        <div style="display: flex; gap: 5px;">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span style="padding: 10px 15px; color: #999;">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <button class="page-btn active" style="padding: 10px 15px; background: var(--color-primary); color: white; border: none; border-radius: 4px; font-weight: 600;">
                                {{ $page }}
                            </button>
                        @else
                            <a href="{{ $url }}" class="page-btn" style="padding: 10px 15px; background: white; color: var(--color-text-primary); border: 1px solid #e5e7eb; border-radius: 4px; text-decoration: none;">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn-next" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px; text-decoration: none;">
                Siguiente
            </a>
        @else
            <button class="btn-next" disabled style="opacity: 0.5; cursor: not-allowed; padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px;">
                Siguiente
            </button>
        @endif
    </nav>
@endif
