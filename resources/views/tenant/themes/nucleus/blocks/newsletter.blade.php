<div {!! $block->attributes() !!} class="arz-newsletter-block flex flex-col gap-4">
    @if($block->title)
        <h4 class="arz-h4" style="color: var(--arz-heading);">{{ $block->title }}</h4>
    @endif
    
    @if($block->text)
        <p class="arz-body-text" style="color: var(--arz-paragraph); opacity: 0.9;">{{ $block->text }}</p>
    @endif

    <form class="flex flex-col sm:flex-row gap-2 w-full mt-2" onsubmit="event.preventDefault(); alert('Newsletter subscription successful!');">
        <input type="email" 
            placeholder="{{ $block->placeholder ?? 'Enter your email' }}" 
            required
            class="flex-1 px-4 py-3 rounded-lg border focus:outline-none transition-colors"
            style="background: var(--arz-bg); color: var(--arz-heading); border-color: var(--arz-border);">
        
        <button type="submit" 
            class="px-6 py-3 rounded-lg font-medium transition-transform hover:-translate-y-0.5
            {{ $block->button_style === 'outline' ? 'border-2 bg-transparent' : '' }}"
            style="
                @if($block->button_style === 'primary' || empty($block->button_style))
                    background: var(--arz-btn-bg, var(--arz-heading));
                    color: var(--arz-bg);
                @elseif($block->button_style === 'secondary')
                    background: var(--arz-border);
                    color: var(--arz-heading);
                @else
                    border-color: var(--arz-heading);
                    color: var(--arz-heading);
                @endif
            ">
            {{ $block->button_text ?? 'Subscribe' }}
        </button>
    </form>
</div>
