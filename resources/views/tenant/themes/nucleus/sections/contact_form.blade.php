<div {!! $section->attributes() !!} class="{{ $section->visibility }}">
    {!! $section->backgrounds() !!}

    <div class="section-content container arz-content" style="position:relative; z-index:10;">

        @php
            $layout = $section->layout_style ?? 'split';
            $formPosition = $section->form_position ?? 'right';
            $showPhone = ($section->show_phone ?? '1') === '1';
            $showSubject = ($section->show_subject ?? '1') === '1';
            $buttonText = $section->button_text ?? 'Send Message';
            $successMessage = $section->success_message ?? 'Thank you! Your message has been sent successfully.';
        @endphp

        <div class="contact-wrapper {{ $layout === 'split' ? 'contact-split' : 'contact-stacked' }} {{ $formPosition === 'left' ? 'contact-reverse' : '' }}">

            {{-- Info Side --}}
            <div class="contact-info">
                {!! $section->blocks() !!}
            </div>

            {{-- Form Side --}}
            <div class="contact-form-wrap">
                <form class="contact-form" data-contact-form data-success="{{ $successMessage }}">
                    <div class="form-row">
                        <div class="form-field">
                            <label class="arz-body-text">Name *</label>
                            <input type="text" name="name" required class="form-input" placeholder="Your name">
                        </div>
                        <div class="form-field">
                            <label class="arz-body-text">Email *</label>
                            <input type="email" name="email" required class="form-input" placeholder="your@email.com">
                        </div>
                    </div>

                    @if($showPhone || $showSubject)
                    <div class="form-row">
                        @if($showPhone)
                        <div class="form-field">
                            <label class="arz-body-text">Phone</label>
                            <input type="tel" name="phone" class="form-input" placeholder="Your phone number">
                        </div>
                        @endif
                        @if($showSubject)
                        <div class="form-field">
                            <label class="arz-body-text">Subject</label>
                            <input type="text" name="subject" class="form-input" placeholder="Message subject">
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="form-field">
                        <label class="arz-body-text">Message *</label>
                        <textarea name="message" required rows="5" class="form-input form-textarea" placeholder="Write your message..."></textarea>
                    </div>

                    <div class="form-submit">
                        <button type="submit" class="arz-btn-primary">{{ $buttonText }}</button>
                    </div>

                    <div class="form-success" style="display:none;">
                        <p class="arz-paragraph" style="color: var(--arz-link);">{{ $successMessage }}</p>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<style>
    .arz-{{ $section->id }} {
        {{ $section->margin }}
    }
    .arz-{{ $section->id }} .section-content {
        {{ $section->padding }}
    }
    .arz-{{ $section->id }} .contact-split {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: {{ $section->gap ?? 48 }}px;
        align-items: start;
    }
    .arz-{{ $section->id }} .contact-reverse {
        direction: rtl;
    }
    .arz-{{ $section->id }} .contact-reverse > * {
        direction: ltr;
    }
    .arz-{{ $section->id }} .contact-stacked {
        max-width: 700px;
        margin: 0 auto;
    }
    .arz-{{ $section->id }} .contact-stacked .contact-info {
        margin-bottom: {{ $section->gap ?? 48 }}px;
    }
    .arz-{{ $section->id }} .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .arz-{{ $section->id }} .form-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 16px;
    }
    .arz-{{ $section->id }} .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--arz-border);
        background: transparent;
        color: var(--arz-heading);
        font-family: inherit;
        font-size: 15px;
        border-radius: 6px;
        outline: none;
        transition: border-color 0.2s;
    }
    .arz-{{ $section->id }} .form-input:focus {
        border-color: var(--arz-link);
    }
    .arz-{{ $section->id }} .form-textarea {
        resize: vertical;
        min-height: 120px;
    }
    .arz-{{ $section->id }} .form-submit {
        margin-top: 8px;
    }
    .arz-{{ $section->id }} .form-success {
        margin-top: 16px;
    }
    @media (max-width: 767px) {
        .arz-{{ $section->id }} {
            {{ $section->marginMobile }}
        }
        .arz-{{ $section->id }} .section-content {
            {{ $section->paddingMobile }}
        }
        .arz-{{ $section->id }} .contact-split {
            grid-template-columns: 1fr;
        }
        .arz-{{ $section->id }} .form-row {
            grid-template-columns: 1fr;
        }
        .arz-{{ $section->id }} .contact-reverse {
            direction: ltr;
        }
    }
</style>
