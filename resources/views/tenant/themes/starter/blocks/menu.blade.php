@php($b = block($block))

<div {!! $b->data !!}>
    <ul class="hidden md:flex">

        @foreach($b->menu->items as $item)
            <li class="relative group">
                <a href="{{ $item->link }}" class="inline-flex items-center arz-link" @if ($item->children->count()) onclick="event.preventDefault()" @endif>
                    {{ $item->name }}
                </a>

                @if($item->children->count())
                    <ul class="absolute top-full left-0 hidden group-hover:block min-w-48 border-rounded mt-2"
                        style="background: var(--arzavo-background)">
                        @foreach($item->children as $child)
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