@php
    $question = $block->question ?? 'Question?';
    $answer = $block->answer ?? 'Answer goes here.';
    $iconStyle = $block->icon_style ?? 'plus';
    $borderStyle = $block->border_style ?? 'bordered';
    $defaultOpen = ($block->default_open ?? '0') === '1';
@endphp

<div {!! $block->attributes() !!}
    class="arz-accordion {{ $borderStyle === 'bordered' ? 'accordion-bordered' : ($borderStyle === 'underline' ? 'accordion-underline' : '') }}"
    data-accordion="{{ $defaultOpen ? 'open' : 'closed' }}">

    <button type="button" class="accordion-trigger" onclick="toggleAccordion(this)">
        <span class="accordion-question arz-h6">{{ $question }}</span>

        @if($iconStyle === 'plus')
            <span class="accordion-icon">
                <i class="fa-solid fa-plus accordion-icon-open"></i>
                <i class="fa-solid fa-minus accordion-icon-close"></i>
            </span>
        @elseif($iconStyle === 'chevron')
            <span class="accordion-icon">
                <i class="fa-solid fa-chevron-down"></i>
            </span>
        @endif
    </button>

    <div class="accordion-content" style="{{ $defaultOpen ? '' : 'display:none;' }}">
        <div class="accordion-answer arz-paragraph">{!! $answer !!}</div>
    </div>
</div>

<style>
    .arz-accordion {
        overflow: hidden;
    }
    .arz-accordion.accordion-bordered {
        border: 1px solid var(--arz-border);
        border-radius: 8px;
        padding: 0;
    }
    .arz-accordion.accordion-underline {
        border-bottom: 1px solid var(--arz-border);
    }
    .accordion-trigger {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 16px 20px;
        background: none;
        border: none;
        cursor: pointer;
        text-align: left;
        color: var(--arz-heading);
        font-family: inherit;
    }
    .accordion-underline .accordion-trigger {
        padding: 16px 0;
    }
    .accordion-trigger:hover {
        opacity: 0.8;
    }
    .accordion-icon {
        flex-shrink: 0;
        font-size: 14px;
        transition: transform 0.3s ease;
        color: var(--arz-paragraph);
    }
    .accordion-icon-close {
        display: none;
    }
    [data-accordion="open"] .accordion-icon-open {
        display: none;
    }
    [data-accordion="open"] .accordion-icon-close {
        display: inline;
    }
    [data-accordion="open"] .accordion-icon .fa-chevron-down {
        transform: rotate(180deg);
    }
    .accordion-content {
        overflow: hidden;
        transition: max-height 0.3s ease;
    }
    .accordion-answer {
        padding: 0 20px 16px;
        color: var(--arz-paragraph);
        line-height: 1.6;
    }
    .accordion-underline .accordion-answer {
        padding: 0 0 16px;
    }
</style>

<script>
    if (typeof toggleAccordion !== 'function') {
        window.toggleAccordion = function(btn) {
            var item = btn.closest('[data-accordion]');
            var content = item.querySelector('.accordion-content');
            var isOpen = item.dataset.accordion === 'open';

            if (isOpen) {
                content.style.display = 'none';
                item.dataset.accordion = 'closed';
            } else {
                content.style.display = 'block';
                item.dataset.accordion = 'open';
            }
        };
    }
</script>
