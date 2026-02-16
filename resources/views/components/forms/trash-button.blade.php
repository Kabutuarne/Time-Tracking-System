@props([
    'confirm' => false,
    'confirmTitle' => 'Are you sure?',
    'confirmMessage' => 'This action cannot be undone.',
])

<button 
    type="button"
    onclick="(function(btn, needsConfirm) {
        const form = btn.closest('form');
        if (needsConfirm) {
            // Wait a tick to ensure Vue is ready
            setTimeout(() => {
                if (window.vueAppInstance && typeof window.vueAppInstance.showConfirm === 'function') {
                    window.vueAppInstance.showConfirm(
                        '{{ addslashes($confirmTitle) }}',
                        '{{ addslashes($confirmMessage) }}',
                        () => form?.submit()
                    );
                } else {
                    console.error('Vue app instance or showConfirm method not available');
                    // Fallback: show browser confirm
                    if (confirm('{{ addslashes($confirmTitle) }}\n\n{{ addslashes($confirmMessage) }}')) {
                        form?.submit();
                    }
                }
            }, 10);
        } else {
            form?.submit();
        }
    })(this, {{ $confirm ? 'true' : 'false' }}); return false;"
    class="relative p-2 border-none bg-transparent cursor-pointer text-base transition-transform duration-200 ease-in-out group/trash scale-[0.6]"
>
    <svg class="w-16 h-16 transition-transform duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)] drop-shadow-md overflow-visible group-hover/trash:scale-[1.08] group-hover/trash:rotate-[3deg] group-active/trash:scale-[0.96] group-active/trash:rotate-[-1deg]"
        viewBox="0 -10 64 74" xmlns="http://www.w3.org/2000/svg">
        <g id="trash-can">
            <rect x="16" y="24" width="32" height="30" rx="3" ry="3" fill="#01BAEF"></rect>
            <g transform-origin="12 18" id="lid-group"
                class="transition-transform duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover/trash:rotate-[-28deg] group-hover/trash:translate-y-[2px] group-active/trash:rotate-[-12deg] group-active:scale-[0.98]">
                <rect x="12" y="12" width="40" height="6" rx="2" ry="2" fill="#01BAEF"></rect>
                <rect x="26" y="8" width="12" height="4" rx="2" ry="2" fill="#01BAEF"></rect>
            </g>
        </g>
    </svg>
</button>