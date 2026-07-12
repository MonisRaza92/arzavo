@php
    $s = $section ?? [];
    $sectionPadding = $s['section_padding'] ?? '4';
    $img_1 = $s['grid_image_1'] ?? null;
    $img_2 = $s['grid_image_2'] ?? null;
    $img_3 = $s['grid_image_3'] ?? null;
    $imgBorder = $s['image_border'] ?? '0';
    $imgRadius = $s['image_border_radius'] ?? '12'; // Default to modern border radius
@endphp

<div {!! $section->attributes() !!}
    style="padding-top: {{ $sectionPadding }}px; padding-bottom: {{ $sectionPadding }}px;"
    class="arz-core flex w-full relative overflow-hidden">
    {!! $section->backgrounds() !!}
    <div class="{{ $section->container }} grid grid-cols-1 lg:grid-cols-3 gap-6 relative z-10">
        <div class="lg:col-span-2 overflow-hidden">
            <div class="nuc-img-zoom w-full h-full" style="border-radius: {{ $imgRadius }}px; border: {{ $imgBorder }}px solid var(--arz-border);">
                <img src="{{ image($img_1) }}" alt="grid image 1" class="w-full h-full object-cover">
            </div>
        </div>
        
        <div class="flex lg:flex-col gap-6 lg:col-span-1 overflow-auto scrollbar h-full">
            <div class="w-11/12 lg:w-full overflow-hidden">
                <div class="nuc-img-zoom w-full h-full" style="border-radius: {{ $imgRadius }}px; border: {{ $imgBorder }}px solid var(--arz-border);">
                    <img src="{{ image($img_2) }}" alt="grid image 2" class="w-full h-full object-cover">
                </div>
            </div>
            
            <div class="w-11/12 lg:w-full overflow-hidden">
                <div class="nuc-img-zoom w-full h-full" style="border-radius: {{ $imgRadius }}px; border: {{ $imgBorder }}px solid var(--arz-border);">
                    <img src="{{ image($img_3) }}" alt="grid image 3" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </div>
</div>
