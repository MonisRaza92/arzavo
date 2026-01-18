<!-- ================= TOAST CONTAINER ================= -->
<div id="toast-container"
    class="fixed bottom-4 right-4 z-50 flex flex-col gap-2
            w-[calc(100%-2rem)] sm:w-96">

    @php
    $toasts = [];

    if ($errors->any()) {
    foreach ($errors->all() as $error) {
    $toasts[] = ['message' => $error, 'type' => 'error'];
    }
    }

    foreach (['success','error','warning','info'] as $type) {
    if (session($type)) {
    $toasts[] = ['message' => session($type), 'type' => $type];
    }
    }
    @endphp

    @foreach ($toasts as $toast)
    <div class="arz-toast arz-toast-{{ $toast['type'] }}">
        <div class="arz-toast-icon">
            @if($toast['type'] === 'success') <i class="fa-solid fa-check"></i>
            @elseif($toast['type'] === 'error') 
            @elseif($toast['type'] === 'warning') <i class="fa-solid fa-exclamation-triangle"></i>
            @else <i class="fa-solid fa-info-circle"></i>
            @endif
        </div>

        <div class="arz-toast-body">
            {{ $toast['message'] }}
        </div>

        <button class="arz-toast-close"
            onclick="removeToast(this.parentElement)">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endforeach

</div>
<style>
    /* ================= ARZAVO TOAST ================= */

    .arz-toast {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 12px 14px;
        border-radius: 4px;
        background: var(--bg-primary);
        border: 1px solid var(--border-primary);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.10);
        animation: arzSlideIn 0.35s ease forwards;
        position: relative;
        overflow: hidden;
    }

    /* Icon */
    .arz-toast-icon {
        font-size: 12px;
        line-height: 1;
        margin-top: 2px;
    }

    /* Message */
    .arz-toast-body {
        flex: 1;
        font-size: 14px;
        color: var(--text-primary);
        line-height: 1.45;
    }

    /* Close button */
    .arz-toast-close {
        font-size: 14px;
        color: var(--text-primary);
        background: transparent;
        border: none;
        cursor: pointer;
    }

    .arz-toast-close:hover {
        color: var(--text-primary);
    }

    /* Variants */
    .arz-toast-success {
        border-left: 16px solid green;
    }

    .arz-toast-error {
        border-left: 16px solid var(--bg-accent);
    }

    .arz-toast-warning {
        border-left: 16px solid #c58400;
    }

    .arz-toast-info {
        border-left: 16px solid var(--bg-accent-secondary);
    }

    /* Animation */
    @keyframes arzSlideIn {
        from {
            opacity: 0;
            transform: translateY(16px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Fade out */
    .arz-toast.hide {
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.4s ease;
    }
</style>
<script>
    /* ================= TOAST JS ================= */

    function removeToast(toast) {
        toast.classList.add('hide');
        setTimeout(() => toast.remove(), 400);
    }

    function autoHideToast(toast, delay = 6000) {
        setTimeout(() => {
            if (toast) removeToast(toast);
        }, delay);
    }

    /* Auto hide all existing toasts */
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll('.arz-toast').forEach(toast => {
            autoHideToast(toast);
        });
    });

    /* ========= OPTIONAL: JS Toast (AJAX / Live use) ========= */
    function showToast(message, type = 'info') {
        const container = document.getElementById('toast-container');

        const icons = {
            success: '<i class="fa-solid fa-check"></i>',
            error: '',
            warning: '<i class="fa-solid fa-exclamation-triangle"></i>',
            info: '<i class="fa-solid fa-info-circle"></i>'
        };

        const toast = document.createElement('div');
        toast.className = `arz-toast arz-toast-${type}`;
        toast.innerHTML = `
        <div class="arz-toast-icon">${icons[type] || icons.info}</div>
        <div class="arz-toast-body">${message}</div>
        <button class="arz-toast-close" onclick="removeToast(this.parentElement)">✕</button>
    `;

        container.appendChild(toast);
        autoHideToast(toast);
    }
</script>
