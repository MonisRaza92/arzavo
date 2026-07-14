@php
    $question = $block->question ?? 'Question?';
    $answer = $block->answer ?? 'Answer goes here.';
    $defaultOpen = ($block->default_open ?? '0') === '1';
    $iconStyle = $block->icon_style ?? 'plus';

    $borderWidth = (int) ($block->border_width ?? 1);
    $borderRadius = (int) ($block->border_radius ?? 12);
    $paddingX = (int) ($block->padding_x ?? 24);
    $paddingY = (int) ($block->padding_y ?? 18);
@endphp

<div {!! $block->attributes() !!}
    class="lex-accordion-item arz-border {{ $defaultOpen ? 'lex-active' : '' }}"
    style="border-width: {{ $borderWidth }}px; border-radius: {{ $borderRadius }}px;">

    <button type="button" class="lex-accordion-trigger"
        style="padding: {{ $paddingY }}px {{ $paddingX }}px;"
        onclick="(function(btn){var item=btn.closest('.lex-accordion-item');if(!item)return;var parent=item.closest('.lex-faq-list')||item.parentElement;if(parent){parent.querySelectorAll('.lex-accordion-item.lex-active').forEach(function(a){if(a!==item)a.classList.remove('lex-active')});}item.classList.toggle('lex-active');})(this)">
        <span class="arz-h6" style="font-size: inherit; font-weight: inherit;">{{ $question }}</span>
        @if($iconStyle !== 'none')
            <span class="lex-accordion-icon {{ $iconStyle === 'chevron' ? 'lex-accordion-icon--chevron' : '' }}">
                @if($iconStyle === 'chevron')
                    <i class="fa-solid fa-chevron-down" style="font-size: 12px;"></i>
                @else
                    <i class="fa-solid fa-plus" style="font-size: 12px;"></i>
                @endif
            </span>
        @endif
    </button>

    <div class="lex-accordion-body">
        <div class="lex-accordion-content arz-paragraph" style="padding: 0 {{ $paddingX }}px {{ $paddingY }}px;">{!! $answer !!}</div>
    </div>
</div>

