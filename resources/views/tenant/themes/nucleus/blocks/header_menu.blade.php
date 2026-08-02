@if($block->menu)
    <div {!! $block->attributes() !!} class="w-auto relative z-[50]" style="{{ $block->spacing }}">
        <ul class="hidden md:flex font-{{ $block->font_weight }} items-center gap-6" style="{{ $block->flexStyle . $block->spacing }}">

            @foreach($block->menu->items as $item)
                <li class="relative group py-2">
                    <a href="{{ $item->link }}" class="inline-flex items-center gap-1 arz-link py-1 hover:opacity-85 transition-opacity" 
                       @if($item->link === '#' || empty($item->link)) onclick="event.preventDefault()" @endif>
                        <span>{{ $item->name }}</span>
                        @if($item->children->count())
                            <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200 group-hover:rotate-180 opacity-70"></i>
                        @endif
                    </a>

                    @if($item->children->count())
                        <ul class="absolute top-full right-0 opacity-0 invisible pointer-events-none transition-all duration-200 delay-150 group-hover:opacity-100 group-hover:visible group-hover:pointer-events-auto group-hover:delay-0 min-w-60 border-rounded mt-1.5 shadow-md p-1.5 arz-border arz-background z-9999 before:content-[''] before:absolute before:top-[-10px] before:left-0 before:right-0 before:h-[10px] before:block"
                            style="background-color: var(--arz-bg, #ffffff);">
                            @foreach($item->children->where('parent_id', $item->id) as $child)
                                <li class="my-0.5">
                                    <a href="{{ $child->link }}" class="block px-3 py-2 text-sm rounded hover:bg-secondary/30 transition-colors arz-link">
                                       {{ $child->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endif