<div {!! $block->attributes() !!} class="overflow-hidden w-full arz-{{ $block->card_type }}" style="">
    <div class="flex flex-col" style="gap:{{$block->block_gap}}px">
        {{-- ✅ CARD CONTENT BLOCKS --}}
        {!! $block->blocks()->render(['data' => $data]) !!}
    </div>
</div>
