@php
    $quote = $block->quote ?? 'Great product!';
    $authorName = $block->author_name ?? 'John Doe';
    $authorRole = $block->author_role ?? 'CEO';
    $authorImage = $block->author_image ?? null;
    $showRating = ($block->show_rating ?? '1') === '1';
    $rating = (int) ($block->rating ?? 5);
    $cardStyle = $block->card_style ?? 'bordered';
@endphp

<div {!! $block->attributes() !!} class="testimonial-card testimonial-{{ $cardStyle }}">

    @if($showRating)
        <div class="testimonial-rating">
            @for($i = 1; $i <= 5; $i++)
                <i class="fa-{{ $i <= $rating ? 'solid' : 'regular' }} fa-star"></i>
            @endfor
        </div>
    @endif

    <div class="testimonial-quote arz-paragraph">{!! $quote !!}</div>

    <div class="testimonial-author">
        @if($authorImage)
            <img src="{{ image($authorImage) }}" alt="{{ $authorName }}" class="testimonial-avatar">
        @else
            <div class="testimonial-avatar-placeholder">
                <i class="fa-solid fa-user"></i>
            </div>
        @endif
        <div class="testimonial-author-info">
            <span class="testimonial-name arz-h6">{{ $authorName }}</span>
            <span class="testimonial-role arz-body-text">{{ $authorRole }}</span>
        </div>
    </div>
</div>

<style>
    .testimonial-card {
        display: flex;
        flex-direction: column;
        gap: 16px;
        padding: 24px;
        height: 100%;
    }
    .testimonial-bordered {
        border: 1px solid var(--arz-border);
        border-radius: 12px;
    }
    .testimonial-filled {
        background: var(--arz-bg);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    .testimonial-minimal {
        padding: 24px 0;
        border-bottom: 1px solid var(--arz-border);
    }
    .testimonial-rating {
        display: flex;
        gap: 3px;
        font-size: 14px;
        color: #f59e0b;
    }
    .testimonial-quote {
        flex: 1;
        color: var(--arz-paragraph);
        line-height: 1.7;
    }
    .testimonial-author {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 8px;
    }
    .testimonial-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }
    .testimonial-avatar-placeholder {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--arz-border);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--arz-paragraph);
        font-size: 18px;
    }
    .testimonial-author-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .testimonial-name {
        color: var(--arz-heading);
    }
    .testimonial-role {
        color: var(--arz-paragraph);
        opacity: 0.7;
    }
</style>
