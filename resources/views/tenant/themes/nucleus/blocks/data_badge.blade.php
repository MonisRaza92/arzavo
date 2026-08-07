@php
    $s = $block['settings'] ?? [];

    $showIcons = filter_var($s['show_icons'] ?? true, FILTER_VALIDATE_BOOLEAN);
    $radius = (int) ($s['border_radius'] ?? 6);
    $mt = (int) ($s['margin_top'] ?? 0);
    $mb = (int) ($s['margin_bottom'] ?? 8);

    $badges = [];

    if (is_string($data->category ?? null) && !empty($data->category)) {
        $badges[] = ['name' => $data->category, 'icon' => 'fa-layer-group', 'bg' => '#eef2ff', 'text' => '#4338ca', 'border' => '#e0e7ff'];
    } elseif (!empty($data->bookCategory->name)) {
        $badges[] = ['name' => $data->bookCategory->name, 'icon' => 'fa-layer-group', 'bg' => '#eef2ff', 'text' => '#4338ca', 'border' => '#e0e7ff'];
    } elseif (!empty($data->category->name)) {
        $badges[] = ['name' => $data->category->name, 'icon' => 'fa-layer-group', 'bg' => '#eef2ff', 'text' => '#4338ca', 'border' => '#e0e7ff'];
    }


    // 2. Academic Stream Category (e.g. NEET, JEE, School)
    if (!empty($data->academicCategory->name)) {
        $badges[] = ['name' => $data->academicCategory->name, 'icon' => 'fa-graduation-cap', 'bg' => '#faf5ff', 'text' => '#7e22ce', 'border' => '#f3e8ff'];
    }

    // 3. Linked Class / Course (e.g. Class 10)
    if (!empty($data->classCourse->name)) {
        $badges[] = ['name' => $data->classCourse->name, 'icon' => 'fa-book-open', 'bg' => '#eff6ff', 'text' => '#1d4ed8', 'border' => '#dbeafe'];
    }

    // 4. Linked Subject (e.g. Physics)
    if (!empty($data->subject->name)) {
        $badges[] = ['name' => $data->subject->name, 'icon' => 'fa-flask', 'bg' => '#fffbeb', 'text' => '#b45309', 'border' => '#fef3c7'];
    }

    // 5. Edition Badge
    if (!empty($data->edition)) {
        $name = $data->edition;
        if (!str_contains(strtolower($name), 'edition')) {
            $name .= ' Edition';
        }
        $badges[] = ['name' => $name, 'icon' => 'fa-certificate', 'bg' => '#ecfdf5', 'text' => '#047857', 'border' => '#a7f3d0'];
    }

@endphp

@if(count($badges) > 0)
    <div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}"
        class="flex flex-wrap items-center gap-2" style="
            margin-top: {{ $mt }}px;
            margin-bottom: {{ $mb }}px;
        ">
        @foreach($badges as $b)
            <span class="px-2.5 py-1 text-xs font-semibold border flex items-center gap-1.5 shadow-xs" style="
                background-color: {{ $b['bg'] }};
                color: {{ $b['text'] }};
                border-color: {{ $b['border'] }};
                border-radius: {{ $radius }}px;
            ">
                @if($showIcons)
                    <i class="fa-solid {{ $b['icon'] }} text-[10px]"></i>
                @endif
                {{ $b['name'] }}
            </span>
        @endforeach
    </div>
@endif