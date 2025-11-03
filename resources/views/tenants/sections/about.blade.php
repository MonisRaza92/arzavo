<section class="py-20 text-center bg-cover bg-center"
    style="background-image: url('{{ $settings['background_image'] ?? '' }}');
           color: {{ $settings['text_color'] ?? '#111' }}">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold mb-3">
            {{ $settings['heading'] ?? 'Welcome to Our Website' }}
        </h1>
        <p class="text-lg mb-6">{{ $settings['subheading'] ?? 'We help you grow your business.' }}</p>

        @if(!empty($settings['button_text']))
        <a href="{{ $settings['button_link'] ?? '#' }}"
            class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition">
            {{ $settings['button_text'] }}
        </a>
        @endif
    </div>
</section>