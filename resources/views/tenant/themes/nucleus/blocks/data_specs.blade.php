@php
    $s = $block['settings'] ?? [];

    $title = $s['title'] ?? 'Book Specifications';
    $showTitle = filter_var($s['show_title'] ?? true, FILTER_VALIDATE_BOOLEAN);
    $columns = (int) ($s['columns'] ?? 3);
    $cardBgColor = $s['card_bg_color'] ?? '#f9fafb';
    $radius = (int) ($s['border_radius'] ?? 8);
    $mt = (int) ($s['margin_top'] ?? 16);
    $mb = (int) ($s['margin_bottom'] ?? 16);

    $gridColsClass = match($columns) {
        2 => 'grid-cols-2',
        4 => 'grid-cols-2 sm:grid-cols-4',
        default => 'grid-cols-2 sm:grid-cols-3'
    };

    $specs = [];
    if (!empty($data->publisher)) $specs['Publisher'] = $data->publisher;
    if (!empty($data->edition)) $specs['Edition'] = $data->edition;
    if (!empty($data->isbn)) $specs['ISBN'] = $data->isbn;
    if (!empty($data->pages_count)) $specs['Pages'] = $data->pages_count;
    if (!empty($data->access_type)) $specs['Access Type'] = strtoupper($data->access_type);
    if (!empty($data->language)) $specs['Language'] = $data->language;
@endphp

@if(count($specs) > 0)
    <div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}"
        class="w-full pt-4 border-t border-gray-100" style="
            margin-top: {{ $mt }}px;
            margin-bottom: {{ $mb }}px;
        ">
        @if($showTitle && $title)
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">{{ $title }}</h3>
        @endif

        <div class="grid {{ $gridColsClass }} gap-3 text-xs">
            @foreach($specs as $label => $val)
                <div class="p-3 border border-gray-100" style="
                    background-color: {{ $cardBgColor }};
                    border-radius: {{ $radius }}px;
                ">
                    <span class="text-gray-400 block mb-0.5">{{ $label }}</span>
                    <span class="font-semibold text-gray-800">{{ $val }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endif
