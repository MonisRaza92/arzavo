<section {!! $section->attributes() !!} class="{{ $section->visibility }} relative overflow-hidden">
    {!! $section->backgrounds() !!}

    {{-- Subtle decorative glowing orb --}}
    <div class="lex-cta-glow lex-cta-glow-1" style="top: -50px; right: -50px; opacity: 0.15;"></div>

    <div class="container {{ $section->alignment === 'center' ? 'text-center mx-auto' : '' }}" style="max-width: 800px; position: relative; z-index: 10;">
        @if($section->title)
            <h2 class="arz-h2 mb-4">{{ $section->title }}</h2>
        @endif
        
        @if($section->description)
            <p class="arz-paragraph mb-8" style="opacity: 0.85;">{{ $section->description }}</p>
        @endif

        <form class="flex flex-col sm:flex-row gap-3 {{ $section->alignment === 'center' ? 'justify-center max-w-lg mx-auto' : 'max-w-lg' }}" onsubmit="event.preventDefault(); alert('Newsletter subscription successful!');">
            <input type="email" 
                placeholder="{{ $section->placeholder ?? 'Enter your email' }}" 
                required
                class="flex-1 lex-input">
            
            <button type="submit" class="arz-btn-primary">
                {{ $section->button_text ?? 'Subscribe' }}
            </button>
        </form>
    </div>
</section>

<style>
    .arz-{{ $section->id }} {
        {{ $section->margin }}
        {{ $section->padding }}
    }

    @media (max-width: 767px) {
        .arz-{{ $section->id }} {
            {{ $section->marginMobile }}
            {{ $section->paddingMobile }}
        }
    }
</style>

