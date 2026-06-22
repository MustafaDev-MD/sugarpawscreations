@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination Navigation"
    class="flex items-center justify-center mt-10">

    <div class="flex items-center gap-2 flex-wrap">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span
                class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-slate-600 cursor-not-allowed text-sm font-semibold">
                ← Prev
            </span>
        @else
            <button
                wire:click="previousPage"
                wire:loading.attr="disabled"
                rel="prev"
                class="px-4 py-2 rounded-xl
                       bg-white/5 backdrop-blur-xl
                       border border-white/10
                       text-slate-300
                       hover:bg-violet-500/20
                       hover:border-violet-500/50
                       hover:text-white
                       transition-all duration-300
                       shadow-lg
                       cursor-pointer">
                ← Prev
            </button>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)

            {{-- Dots --}}
            @if (is_string($element))
                <span
                    class="px-4 py-2 text-slate-500">
                    {{ $element }}
                </span>
            @endif

            {{-- Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)

                    @if ($page == $paginator->currentPage())
                        <span
                            class="px-4 py-2 rounded-xl
                                   bg-gradient-to-r
                                   from-violet-500
                                   via-purple-500
                                   to-fuchsia-500
                                   text-white
                                   font-bold
                                   shadow-lg
                                   shadow-fuchsia-500/30">
                            {{ $page }}
                        </span>
                    @else
                        <button
                            wire:click="gotoPage({{ $page }})"
                            class="px-4 py-2 rounded-xl
                                   bg-white/5
                                   backdrop-blur-xl
                                   border border-white/10
                                   text-slate-300
                                   hover:bg-fuchsia-500/20
                                   hover:border-fuchsia-500/50
                                   hover:text-white
                                   hover:shadow-lg
                                   hover:shadow-fuchsia-500/20
                                   transition-all duration-300
                                   cursor-pointer">
                            {{ $page }}
                        </button>
                    @endif

                @endforeach
            @endif

        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <button
                wire:click="nextPage"
                wire:loading.attr="disabled"
                rel="next"
                class="px-4 py-2 rounded-xl
                       bg-white/5 backdrop-blur-xl
                       border border-white/10
                       text-slate-300
                       hover:bg-violet-500/20
                       hover:border-violet-500/50
                       hover:text-white
                       transition-all duration-300
                       shadow-lg
                       cursor-pointer">
                Next →
            </button>
        @else
            <span
                class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-slate-600 cursor-not-allowed text-sm font-semibold">
                Next →
            </span>
        @endif

    </div>
</nav>
@endif