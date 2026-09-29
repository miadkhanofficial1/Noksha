@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center">
        <div class="flex items-center gap-1.5 font-monospace">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-slate-600 bg-slate-900/60 border border-slate-800/80 cursor-not-allowed select-none" aria-hidden="true">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-slate-300 bg-slate-900 border border-slate-800 hover:border-purple-500/50 hover:bg-slate-800 hover:text-white transition-all shadow-sm" aria-label="{{ __('pagination.previous') }}">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span aria-disabled="true">
                        <span class="inline-flex items-center justify-center w-9 h-9 text-slate-500 font-bold select-none">{{ $element }}</span>
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-white font-extrabold text-sm shadow-md" style="background: linear-gradient(135deg, #7C3AED 0%, #6366F1 100%); border: 1px solid rgba(139, 92, 246, 0.5);">{{ $page }}</span>
                            </span>
                        @else
                            <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-slate-300 bg-slate-900 border border-slate-800 hover:border-purple-500/50 hover:bg-slate-800 hover:text-white transition-all text-sm font-semibold shadow-sm" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-slate-300 bg-slate-900 border border-slate-800 hover:border-purple-500/50 hover:bg-slate-800 hover:text-white transition-all shadow-sm" aria-label="{{ __('pagination.next') }}">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-slate-600 bg-slate-900/60 border border-slate-800/80 cursor-not-allowed select-none" aria-hidden="true">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </span>
            @endif
        </div>
    </nav>
@endif
