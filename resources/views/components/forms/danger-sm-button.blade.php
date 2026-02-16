@props([
    'secondary' => false,
    'confirm' => false,
    'confirmTitle' => 'Are you sure?',
    'confirmMessage' => 'This action cannot be undone.',
])

<div class="inline-block">
    <div class="relative group/sm-button">
        @if($attributes->has('href'))
            <a
                {{ $attributes->merge(['class' => "relative inline-block p-px text-sm font-semibold leading-4 text-textcol " . ($secondary ? "bg-accent" : "bg-dark") . " shadow-lg cursor-pointer rounded-lg shadow-zinc-900 transition-transform duration-300 ease-in-out hover:scale-105 active:scale-95"]) }}
                @if($confirm)
                    onclick="(function(el, needsConfirm) {
                        if (needsConfirm) {
                            event.preventDefault();
                            const form = el.closest('form');
                            const href = el.href;
                            setTimeout(() => {
                                if (window.vueAppInstance && typeof window.vueAppInstance.showConfirm === 'function') {
                                    window.vueAppInstance.showConfirm(
                                        '{{ addslashes($confirmTitle) }}',
                                        '{{ addslashes($confirmMessage) }}',
                                        () => {
                                            if(form) {
                                                form.submit();
                                            } else {
                                                window.location = href;
                                            }
                                        }
                                    );
                                } else {
                                    console.error('Vue app instance not available');
                                    if (confirm('{{ addslashes($confirmTitle) }}\n\n{{ addslashes($confirmMessage) }}')) {
                                        if(form) {
                                            form.submit();
                                        } else {
                                            window.location = href;
                                        }
                                    }
                                }
                            }, 10);
                        }
                    })(this, {{ $confirm ? 'true' : 'false' }}); return false;"
                @endif
            >
        @else
            <button
                {{ $attributes->merge(['type' => 'submit', 'class' => "relative inline-block p-px text-sm font-semibold leading-4 text-textcol " . ($secondary ? "bg-accent" : "bg-dark") . " shadow-lg cursor-pointer rounded-lg shadow-zinc-900 transition-transform duration-300 ease-in-out hover:scale-105 active:scale-95"]) }}
                @if($confirm)
                    onclick="(function(btn, needsConfirm) {
                        if (needsConfirm) {
                            event.preventDefault();
                            const form = btn.closest('form');
                            setTimeout(() => {
                                if (window.vueAppInstance && typeof window.vueAppInstance.showConfirm === 'function') {
                                    window.vueAppInstance.showConfirm(
                                        '{{ addslashes($confirmTitle) }}',
                                        '{{ addslashes($confirmMessage) }}',
                                        () => form?.submit()
                                    );
                                } else {
                                    console.error('Vue app instance not available');
                                    if (confirm('{{ addslashes($confirmTitle) }}\n\n{{ addslashes($confirmMessage) }}')) {
                                        form?.submit();
                                    }
                                }
                            }, 10);
                        }
                    })(this, {{ $confirm ? 'true' : 'false' }}); return false;"
                @endif
            >
        @endif

            <span
                class="absolute inset-0 rounded-lg bg-gradient-to-r from-primary via-primary to-secondary p-[2px] opacity-0 transition-opacity duration-350 group-hover/sm-button:opacity-100"></span>

            <span class="relative z-10 block px-3 py-1 rounded-lg {{ $secondary ? 'bg-accent' : 'bg-darker' }}">
                <div class="relative z-10 flex items-center space-x-1">
                    <span class="transition-all duration-350">{{ $slot }}</span>
                </div>
            </span>

        @if($attributes->has('href'))
            </a>
        @else
            </button>
        @endif
    </div>
</div>