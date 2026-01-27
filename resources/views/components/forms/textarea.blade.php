<!-- From Uiverse.io by danilppzz -->
<div class="flex min-h-[44px] text-[14px] text-textcol/60 w-full">
    <textarea rows="1" maxlength="255" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px'"
        class="input w-[400px] text-textcol bg-slate-950/50 px-3 py-2 rounded-lg border border-textcol/10
               focus:outline-none focus:ring-2 focus:ring-primary
               focus:ring-offset-2 focus:ring-offset-darker
               transition-all duration-200 ease-in-out
               leading-[20px] resize-none overflow-hidden" {{ $attributes }}>{{ $slot }}</textarea>
</div>