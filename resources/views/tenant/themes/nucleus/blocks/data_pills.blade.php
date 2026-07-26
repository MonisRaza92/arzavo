<div {!! $block->attributes() !!} class="flex gap-2 mt-3 flex-wrap">
    @foreach ($data->classCourses as $item)
        <span class="arz-border rounded-full py-1 px-3">
            {{ $item->name }}
        </span>
    @endforeach
</div>