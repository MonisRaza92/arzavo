<section {!! $section->attributes() !!} class="{{ $section->visibility }} arz-newsletter-banner relative">
    
    <div class="container {{ $section->alignment === 'center' ? 'text-center mx-auto' : '' }}" style="max-width: 800px; position: relative; z-index: 10;">
        @if($section->title)
            <h2 class="arz-h2 mb-4">{{ $section->title }}</h2>
        @endif
        
        @if($section->description)
            <p class="arz-body-text mb-8" style="opacity: 0.85;">{{ $section->description }}</p>
        @endif

        <form class="flex flex-col sm:flex-row gap-3 {{ $section->alignment === 'center' ? 'justify-center max-w-lg mx-auto' : 'max-w-lg' }}" onsubmit="event.preventDefault(); alert('Newsletter subscription successful!');">
            <input type="email" 
                placeholder="{{ $section->placeholder ?? 'Enter your email' }}" 
                required
                class="flex-1 px-5 py-4 rounded-xl border focus:outline-none transition-colors shadow-sm"
                style="background: var(--arz-bg); color: var(--arz-heading); border-color: rgba(156, 163, 175, 0.3);">
            
            <button type="submit" 
                class="px-8 py-4 rounded-xl font-semibold transition-transform hover:-translate-y-0.5 shadow-sm"
                style="background: var(--arz-btn-bg, var(--arz-heading)); color: var(--arz-bg);">
                {{ $section->button_text ?? 'Subscribe' }}
            </button>
        </form>
    </div>
</section>

<style>
    .arz-{{ $section->id }} {
        {{ $section->margin }}
        {{ $section->padding }}
        background: var(--arz-bg); /* Use the scheme's background */
    }

    @media (max-width: 767px) {
        .arz-{{ $section->id }} {
            {{ $section->marginMobile }}
            {{ $section->paddingMobile }}
        }
    }
</style>
