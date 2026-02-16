{{-- @props('pagename') --}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="mt-8">

        {{-- Mobile Pagination --}}
        <div class="flex gap-3 items-center justify-between sm:hidden">

            @if ($paginator->onFirstPage())
                <span
                    class="relative inline-block p-px font-semibold leading-6 text-slate-500 bg-slate-900/40 cursor-not-allowed rounded-xl shadow-lg opacity-50">
                    <span class="relative z-10 block px-6 py-3 rounded-xl bg-slate-950/40">
                        <div wire:ignore class="relative z-10 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>{!! __('pagination.previous') !!}</span>
                        </div>
                    </span>
                </span>
            @else
                <button wire:click="previousPage('{{ $pagename }}')" type="button" rel="prev"
                    class="relative inline-block p-px font-semibold leading-6 text-textcol bg-slate-950/40 shadow-2xl cursor-pointer rounded-xl shadow-zinc-900 transition-transform duration-300 ease-in-out hover:scale-105 active:scale-95 group/button">
                    <span
                        class="absolute inset-0 rounded-xl bg-gradient-to-r from-primary via-primary to-secondary p-[2px] opacity-0 transition-opacity duration-350 group-hover/button:opacity-100"></span>
                    <span class="relative z-10 block px-6 py-3 rounded-xl bg-darker">
                        <div class="relative z-10 flex items-center space-x-2">
                            <svg class="w-5 h-5 transition-transform duration-350 group-hover/button:-translate-x-1"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="transition-all duration-350">{!! __('pagination.previous') !!}</span>
                        </div>
                    </span>
                </button>
            @endif

            @if ($paginator->hasMorePages())
                <button wire:click="nextPage('{{ $pagename }}')" type="button" rel="next"
                    class="relative inline-block p-px font-semibold leading-6 text-textcol bg-slate-950/40 shadow-2xl cursor-pointer rounded-xl shadow-zinc-900 transition-transform duration-300 ease-in-out hover:scale-105 active:scale-95 group/button">
                    <span
                        class="absolute inset-0 rounded-xl bg-gradient-to-r from-primary via-primary to-secondary p-[2px] opacity-0 transition-opacity duration-350 group-hover/button:opacity-100"></span>
                    <span class="relative z-10 block px-6 py-3 rounded-xl bg-darker">
                        <div class="relative z-10 flex items-center space-x-2">
                            <span class="transition-all duration-350">{!! __('pagination.next') !!}</span>
                            <svg class="w-5 h-5 transition-transform duration-350 group-hover/button:translate-x-1"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </span>
                </button>
            @else
                <span
                    class="relative inline-block p-px font-semibold leading-6 text-slate-500 bg-slate-900/40 cursor-not-allowed rounded-xl shadow-lg opacity-50">
                    <span class="relative z-10 block px-6 py-3 rounded-xl bg-slate-950/40">
                        <div class="relative z-10 flex items-center space-x-2">
                            <span>{!! __('pagination.next') !!}</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 011.414-1.414l4 4a1 1 010 1.414l-4 4a1 1 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </span>
                </span>
            @endif

        </div>

        {{-- Desktop Pagination --}}
        <div class="hidden sm:flex sm:flex-col sm:gap-4 sm:items-center">

            {{-- Results Info --}}
            <div class="text-center">
                <p class="text-sm text-textcol2 leading-5">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-semibold text-primary">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-semibold text-primary">{{ $paginator->lastItem() }}</span>
                    @else
                        <span class="font-semibold text-primary">{{ $paginator->count() }}</span>
                    @endif
                    {!! __('of') !!}
                    <span class="font-semibold text-secondary">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            {{-- Page Links --}}
            <div class="flex items-center gap-2">

                {{-- Previous --}}
                @if ($paginator->onFirstPage())
                    <span
                        class="relative inline-flex items-center justify-center w-10 h-10 font-semibold text-slate-500 bg-slate-900/40 cursor-not-allowed rounded-lg shadow-lg opacity-50 ring-1 ring-white/5">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M12.707 5.293a1 1 010 1.414L9.414 10l3.293 3.293a1 1 01-1.414 1.414l-4-4a1 1 010-1.414l4-4a1 1 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                @else
                    <button wire:click="previousPage('{{ $pagename }}')" type="button"
                        class="relative inline-flex items-center justify-center w-10 h-10 p-px font-semibold text-textcol bg-slate-950/40 shadow-xl cursor-pointer rounded-lg transition-all duration-300 ease-in-out hover:scale-110 active:scale-95 group/button"
                        aria-label="{{ __('pagination.previous') }}">
                        <span
                            class="absolute inset-0 rounded-lg bg-gradient-to-r from-primary via-primary to-secondary p-[2px] opacity-0 transition-opacity duration-350 group-hover/button:opacity-100"></span>
                        <span class="relative z-10 flex items-center justify-center w-full h-full bg-darker rounded-[6px]">
                            <svg class="w-5 h-5 transition-transform duration-350 group-hover/button:-translate-x-0.5"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12.707 5.293a1 1 010 1.414L9.414 10l3.293 3.293a1 1 01-1.414 1.414l-4-4a1 1 010-1.414l4-4a1 1 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                    </button>
                @endif

                {{-- Numbers --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span
                            class="inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-textcol2 bg-slate-950/40 rounded-lg ring-1 ring-white/5">
                            {{ $element }}
                        </span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page"
                                    class="relative inline-flex items-center justify-center w-10 h-10 p-[2px] font-semibold text-textcol bg-gradient-to-r from-primary via-primary to-secondary shadow-xl rounded-lg">
                                    <span class="flex items-center justify-center w-full h-full bg-darker rounded-[6px]">
                                        {{ $page }}
                                    </span>
                                </span>
                            @else
                                <button wire:click="gotoPage({{ $page }}, '{{ $pagename }}')" type="button"
                                    class="relative inline-flex items-center justify-center w-10 h-10 p-px font-semibold text-textcol bg-slate-950/40 shadow-lg cursor-pointer rounded-lg transition-all duration-300 ease-in-out hover:scale-110 active:scale-95 group/button"
                                    aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    <span
                                        class="absolute inset-0 rounded-lg bg-gradient-to-r from-primary via-primary to-secondary p-[2px] opacity-0 transition-opacity duration-350 group-hover/button:opacity-100"></span>
                                    <span
                                        class="relative z-10 flex items-center justify-center w-full h-full bg-darker rounded-[6px]">{{ $page }}</span>
                                </button>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <button wire:click="nextPage('{{ $pagename }}')" type="button"
                        class="relative inline-flex items-center justify-center w-10 h-10 p-px font-semibold text-textcol bg-slate-950/40 shadow-xl cursor-pointer rounded-lg transition-all duration-300 ease-in-out hover:scale-110 active:scale-95 group/button"
                        aria-label="{{ __('pagination.next') }}">
                        <span
                            class="absolute inset-0 rounded-lg bg-gradient-to-r from-primary via-primary to-secondary p-[2px] opacity-0 transition-opacity duration-350 group-hover/button:opacity-100"></span>
                        <span class="relative z-10 flex items-center justify-center w-full h-full bg-darker rounded-[6px]">
                            <svg class="w-5 h-5 transition-transform duration-350 group-hover/button:translate-x-0.5"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 010-1.414L10.586 10 7.293 6.707a1 1 011.414-1.414l4 4a1 1 010 1.414l-4 4a1 1 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                    </button>
                @else
                    <span
                        class="relative inline-flex items-center justify-center w-10 h-10 font-semibold text-slate-500 bg-slate-900/40 cursor-not-allowed rounded-lg shadow-lg opacity-50 ring-1 ring-white/5">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 010-1.414L10.586 10 7.293 6.707a1 1 011.414-1.414l4 4a1 1 010 1.414l-4 4a1 1 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif

            </div>
        </div>
    </nav>
@endif