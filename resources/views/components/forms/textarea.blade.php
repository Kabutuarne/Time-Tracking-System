<!-- From Uiverse.io by danilppzz -->
<div class="flex min-h-[100px] text-[14px] text-textcol/60 w-full">
    <textarea rows="1" maxlength="255"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px'"
        {{ $attributes->merge(['class' => 'input w-full text-textcol bg-slate-950/50 px-3 py-2 rounded-lg border border-textcol/10
               hover:border-primary hover:bg-slate-950/70
               focus:outline-none focus:ring-2 focus:ring-primary
               focus:ring-offset-2 focus:ring-offset-darker
               transition-all duration-150 ease-out
               focus:duration-350 focus:ease-in-out
               leading-[20px] resize-none overflow-hidden']) }}>{{ $slot }}</textarea>
</div>
