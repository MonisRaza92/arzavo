@php
    $quote = $block->quote ?? 'Great product!';
    $authorName = $block->author_name ?? 'John Doe';
    $authorRole = $block->author_role ?? 'CEO';
    $authorImage = $block->author_image ?? null;
    $showRating = ($block->show_rating ?? '1') === '1';
    $rating = (int) ($block->rating ?? 5);
    $cardStyle = $block->card_style ?? 'bordered';
@endphp

<div {!! $block->attributes() !!} class="nuc-testimonial-card arz-border" style="border-radius:{{ $block->border_radius ?? 6 }}px;border-width:{{ $block->border_width ?? 1 }}px;">

    {{-- Decorative quote mark --}}
    <span class="nuc-testimonial-quote">"</span>

    @if($showRating)
        <div class="nuc-testimonial-stars">
            @for($i = 1; $i <= 5; $i++)
                <i class="fa-{{ $i <= $rating ? 'solid' : 'regular' }} fa-star" style="font-size: 14px;"></i>
            @endfor
        </div>
    @endif

    <div class="testimonial-quote-text arz-paragraph" style="flex: 1; line-height: 1.7; position: relative; z-index: 1;">{!! $quote !!}</div>

    <div class="testimonial-author" style="display: flex; align-items: center; gap: 12px; margin-top: 12px;">
        @if($authorImage)
            <img src="{{ image($authorImage) }}" alt="{{ $authorName }}" class="nuc-testimonial-avatar">
        @else
            <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--arz-border); display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: var(--arz-paragraph); font-size: 18px;">
                <i class="fa-solid fa-user"></i>
            </div>
        @endif
        <div style="display: flex; flex-direction: column; gap: 2px;">
            <span class="arz-h6" style="font-size: 15px; font-weight: 600;">{{ $authorName }}</span>
            <span class="arz-body-text" style="opacity: 0.7;">{{ $authorRole }}</span>
        </div>
    </div>
</div>

<style>
    .testimonial-bordered.nuc-testimonial-card {
        border: 1px solid var(--arz-border);
    }
    .testimonial-filled.nuc-testimonial-card {
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .testimonial-minimal.nuc-testimonial-card {
        border: none;
        border-radius: 0;
        border-bottom: 1px solid var(--arz-border);
        padding: 24px 0;
    }
    .testimonial-minimal.nuc-testimonial-card:hover {
        transform: none;
        box-shadow: none;
    }
</style>
