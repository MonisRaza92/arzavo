@if($block->menu)
    @php
        $direction = $block->direction ?? 'flex-col';
        $flexClass = $direction === 'flex-row' ? 'flex flex-row flex-wrap items-center' : 'flex flex-col items-start';
        $showTitle = ($block->show_title ?? '1') == '1';
        $textSize  = $block->text_size ?? 'arz-paragraph';
    @endphp
    <div {!! $block->attributes() !!} class="w-auto" style="{{ $block->spacing }}">
        @if($showTitle)
            <h2 class="text-lg uppercase mb-4 font-bold" style="color: var(--arz-heading);">{{ $block->menu->name }}</h2>
        @endif
        <ul class="{{ $flexClass }} font-{{ $block->font_weight }}" style="{{ $block->flexStyle . $block->spacing }}">
            @foreach($block->menu->items as $item)
                <li class="relative">
                    <a href="{{ $item->link }}" class="inline-flex items-center arz-link {{ $textSize }}">
                        {{ $item->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif