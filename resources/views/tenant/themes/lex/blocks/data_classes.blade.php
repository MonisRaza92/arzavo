<div {!! $block->attributes() !!} class="" style="{{ $block->padding }}">
    @if (!empty($data->classCourses) && $data->classCourses->isNotEmpty())
        <div class="flex flex-wrap gap-2">
            @foreach($data->classCourses as $class)
                <span class="arz-border"
                    style="font-weight:{{ $block->font_weight ?? 'normal' }};font-size:{{ $block->font_size ?? '12' }}px;border-width:{{ $block->border_width }}px;border-radius:{{ $block->border_radius }}px;padding:{{ $block->inner_gap }}px calc( {{ $block->inner_gap }}px + 10px );background:{{ $block->bg }};">
                    {{ $class->name }}
                </span>
            @endforeach
        </div>
    @else
        <span class="text-tertiary text-xs">No classes available</span>
    @endif
</div>
