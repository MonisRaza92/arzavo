@php($settings = section($section))

<div data-section-id="{{ $section['id'] }}" data-name="{{ $section['name'] }}"
    class="section relative overflow-hidden {{ $settings['visibility'] }}"
    style="{{ scheme($section['color_scheme']) }} {{ $settings['background']['style'] }}">

    <x-section.background :bg="$settings['background']" />

    <div class="section-content relative z-30 {{ $settings['layout']['class'] }} {{ $settings['spacing']['class'] }}"
        style="{{ $settings['spacing']['style'] }}">
        {!! renderBlocks($section['blocks']) !!}
    </div>
</div>
