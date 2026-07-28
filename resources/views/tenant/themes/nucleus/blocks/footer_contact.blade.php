<div {!! $block->attributes() !!} class="flex flex-col" style="gap: {{ $block->gap ?? 16 }}px;">
    {!! $block->blocks() !!}
</div>
