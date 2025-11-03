@php
$card = $section->settings ?? [];

$bg = $card['card_bg'] ?? '#ffffff';
$border = $card['card_border'] ?? '1px solid #e0e0e0';
$radius = $card['card_radius'] ?? '12px';
$shadow = $card['card_shadow'] ?? '0 2px 8px rgba(0,0,0,0.1)';
$padding = $card['card_padding'] ?? '24px';
$margin = $card['card_margin'] ?? '20px 0';

$imagePos = $card['image_position'] ?? 'left';
$flexDir = $imagePos === 'right' ? 'flex-row-reverse' : 'flex-row';

$image = $card['image'] ?? null;

$title = $card['title'] ?? '';
$text = $card['text'] ?? '';

@endphp

<section class="container mx-auto">
    <div class="flex flex-col md:{{ $flexDir }} items-center"
        style="background: {{ $bg }}; border: {{ $border }}; border-radius: {{ $radius }}; 
        box-shadow: {{ $shadow }}; padding: {{ $padding }}; margin: {{ $margin }};">

        {{-- IMAGE --}}
        @if($image)
        <div class="w-full md:w-1/2 p-3">
            <img src="{{ asset('storage/' . $image) }}" class="w-full rounded-lg object-cover">
        </div>
        @endif

        {{-- CONTENT --}}
        <div class="w-full md:w-1/2 p-3">

            @if($title)
            <h2 style="color: {{ $card['title_color'] ?? '#111' }}; 
                font-size: {{ $card['title_size'] ?? '28px' }};">
                {{ $title }}
            </h2>
            @endif

            @if($text)
            <p class="mt-3"
                style="color: {{ $card['text_color'] ?? '#555' }}; 
               font-size: {{ $card['text_size'] ?? '16px' }};">
                {{ $text }}
            </p>
            @endif

            @if($card['btn_text'] ?? null)
            <a href="{{ $card['btn_link'] ?? '#' }}"
                class="inline-block mt-4 font-medium"
                style="background: {{ $card['btn_bg'] ?? '#007bff' }}; 
                color: {{ $card['btn_color'] ?? '#fff' }};
                padding: {{ $card['btn_padding'] ?? '10px 20px' }};
                border-radius: {{ $card['btn_radius'] ?? '6px' }};">
                {{ $card['btn_text'] }}
            </a>
            @endif

        </div>

    </div>
</section>