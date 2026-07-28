@php
    $title = $block->title ?? 'Send Us a Message';
    $buttonText = $block->button_text ?? 'Send Message';
    
    $buttonStyle = $block->button_style ?? 'arz-btn-primary';
    $buttonColor = !empty($block->button_color) ? $block->button_color : '#4f46e5';
    $buttonTextColor = !empty($block->button_text_color) ? $block->button_text_color : '#ffffff';
    
    $border = is_numeric($block->border) ? $block->border : 1;
    $radius = is_numeric($block->radius) ? $block->radius : 10;
    $padding = is_numeric($block->padding) ? $block->padding : 20;
    
    $bgColor = !empty($block->bg_color) ? $block->bg_color : '#ffffff';
@endphp

<div {!! $block->attributes() !!} class="w-full arz-border"
     style="border-width: {{ $border }}px; border-radius: {{ $radius }}px; background: {{ $bgColor }}; padding: {{ $padding }}px;">
    @if($title)
        <h3 class="text-xl font-bold text-slate-800 mb-6">{{ $title }}</h3>
    @endif

    <form class="space-y-4" action="{{ route_to('contact.form') }}" method="POST" onsubmit="submitContactForm(event, this)">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">Your Name</label>
                <input type="text" name="name" required class="arz-input">
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">Your Email</label>
                <input type="email" name="email" required class="arz-input">
            </div>
        </div>

        <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">Subject</label>
            <input type="text" name="subject" required class="arz-input">
        </div>

        <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">Message</label>
            <textarea name="message" rows="4" required class="arz-input resize-none"></textarea>
        </div>

        @if($buttonStyle === 'custom')
            <button type="submit" 
                    style="background-color: {{ $buttonColor }}; color: {{ $buttonTextColor }};"
                    class="w-full py-3 bg-indigo-600 hover:opacity-90 active:opacity-100 font-semibold rounded shadow transition-opacity text-sm uppercase tracking-wider mt-2">
                {{ $buttonText }}
            </button>
        @else
            <button type="submit" 
                    class="w-full py-3.5 font-semibold rounded shadow transition-opacity text-sm uppercase tracking-wider mt-2 {{ $buttonStyle }}">
                {{ $buttonText }}
            </button>
        @endif
    </form>
</div>

<script>
if (typeof submitContactForm !== 'function') {
    function submitContactForm(event, form) {
        event.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        const originalText = btn.innerText;
        btn.disabled = true;
        btn.innerText = 'Sending...';

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: form.querySelector('input[name="name"]').value,
                email: form.querySelector('input[name="email"]').value,
                subject: form.querySelector('input[name="subject"]').value,
                message: form.querySelector('textarea[name="message"]').value
            })
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerText = originalText;
            if (data.success) {
                if (typeof showToast === 'function') {
                    showToast(data.message);
                } else {
                    alert(data.message);
                }
                form.reset();
            } else {
                if (typeof showToast === 'function') {
                    showToast('Something went wrong. Please try again.');
                } else {
                    alert('Something went wrong. Please try again.');
                }
            }
        })
        .catch(error => {
            btn.disabled = false;
            btn.innerText = originalText;
            if (typeof showToast === 'function') {
                showToast('An error occurred. Please try again.');
            } else {
                alert('An error occurred. Please try again.');
            }
        });
    }
}
</script>
