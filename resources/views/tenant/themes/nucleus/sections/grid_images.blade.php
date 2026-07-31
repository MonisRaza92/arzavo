@php
    $s = $section ?? [];
    $img_1 = $s['grid_image_1'] ?? null;
    $img_2 = $s['grid_image_2'] ?? null;
    $img_3 = $s['grid_image_3'] ?? null;
    $imgBorder = $s['image_border'] ?? '0';
    $imgRadius = $s['image_border_radius'] ?? '12'; // Default to modern border radius
@endphp

<div {!! $section->attributes() !!}
    style="{{ $section->padding . $section->margin}}"
    class="arz-core flex w-full relative overflow-hidden">
    {!! $section->backgrounds() !!}
    <div class="{{ $section->container }} grid grid-cols-1 lg:grid-cols-3 gap-4 relative z-10">
        <div class="lg:col-span-2 overflow-hidden">
            <div class="w-full h-full overflow-hidden" style="border-radius: {{ $imgRadius }}px; border: {{ $imgBorder }}px solid var(--arz-border);">
                <img src="{{ image($img_1) }}" alt="grid image 1" class="w-full h-full object-cover">
            </div>
        </div>
        
        <div class="flex lg:flex-col gap-4 lg:col-span-1 overflow-auto scrollbar h-full">
            <div class="w-11/12 lg:w-full overflow-hidden">
                <div class="w-full h-full overflow-hidden" style="border-radius: {{ $imgRadius }}px; border: {{ $imgBorder }}px solid var(--arz-border);">
                    <img src="{{ image($img_2) }}" alt="grid image 2" class="w-full h-full object-cover">
                </div>
            </div>
            
            <div class="w-11/12 lg:w-full overflow-hidden">
                <div class="w-full h-full overflow-hidden" style="border-radius: {{ $imgRadius }}px; border: {{ $imgBorder }}px solid var(--arz-border);">
                    <img src="{{ image($img_3) }}" alt="grid image 3" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </div>
</div>
