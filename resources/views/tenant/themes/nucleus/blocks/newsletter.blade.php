<div {!! $block->attributes() !!} class="arz-newsletter-block flex flex-col gap-4">
    @if($block->title)
        <h4 class="arz-h4" style="color: var(--arz-heading);">{{ $block->title }}</h4>
    @endif
    
    @if($block->text)
        <p class="arz-body-text" style="color: var(--arz-paragraph); opacity: 0.9;">{{ $block->text }}</p>
    @endif

    <form class="flex flex-col sm:flex-row gap-2 w-full mt-2" action="{{ route_to('newsletter.submit') }}" method="POST" onsubmit="submitNewsletterForm(event, this)">
        @csrf
        <input type="email" 
            name="email"
            placeholder="{{ $block->placeholder ?? 'Enter your email' }}" 
            required
            class="flex-1 arz-input">
        
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

<script>
if (typeof submitNewsletterForm !== 'function') {
    function submitNewsletterForm(event, form) {
        event.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        const originalText = btn.innerText;
        btn.disabled = true;
        btn.innerText = 'Subscribing...';

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                email: form.querySelector('input[name="email"]').value
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
