@if($block->menu)
    <div {!! $block->attributes() !!} class="w-auto" style="{{ $block->spacing }}">
        <ul class=" hidden md:flex font-{{ $block->font_weight }} items-center gap-6" style="{{ $block->flexStyle . $block->spacing }}">

            @foreach($block->menu->items as $item)
                <li class="relative group">
                    <a href="{{ $item->link }}" class="inline-flex items-center arz-link" @if ($item->children->count())
                    onclick="event.preventDefault()" @endif>
                        {{ $item->name }}
                    </a>

                    @if($item->children->count())
                        <ul class="absolute top-full left-0 hidden group-hover:block min-w-48 border-rounded mt-2 arz-background">
                            @foreach($item->children->where('parent_id', $item->id) as $child)
                                <li>
                                    <a href="{{ $child->link }}" class="block px-4 py-2 arz-link">
                                        {{ $child->title }}
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
