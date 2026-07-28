@php
    $placeholder = $block->placeholder ?? 'Enter your email address';
    $buttonText = $block->button_text ?? 'Subscribe';
    
    $buttonStyle = $block->button_style ?? 'arz-btn-primary';
    $buttonColor = !empty($block->button_color) ? $block->button_color : '#4f46e5';
    $buttonTextColor = !empty($block->button_text_color) ? $block->button_text_color : '#ffffff';
    
    $layout = $block->layout_style ?? 'horizontal';
@endphp

<div {!! $block->attributes() !!} class="w-full">
    <form class="flex {{ $layout === 'horizontal' ? 'flex-col sm:flex-row' : 'flex-col' }} gap-3 w-full" 
          action="{{ route_to('newsletter.submit') }}" method="POST" onsubmit="submitNewsletterForm(event, this)">
        @csrf
        
        <input type="email" 
               name="email"
               placeholder="{{ $placeholder }}" 
               required
               class="flex-1 arz-input">
        
        @if($buttonStyle === 'custom')
            <button type="submit" 
                    style="background-color: {{ $buttonColor }}; color: {{ $buttonTextColor }};"
                    class="px-5 py-2.5 text-sm rounded font-medium transition-opacity hover:opacity-90 active:opacity-100 whitespace-nowrap shadow-sm">
                {{ $buttonText }}
            </button>
        @else
            <button type="submit" 
                    class="px-5 py-2.5 text-sm font-medium transition-opacity hover:opacity-90 active:opacity-100 whitespace-nowrap shadow-sm {{ $buttonStyle }}">
                {{ $buttonText }}
            </button>
        @endif
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
