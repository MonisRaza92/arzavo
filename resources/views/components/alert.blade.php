<!-- TOAST CONTAINER -->
<div id="toast-container" class="fixed bottom-4 right-4 z-50 flex flex-col gap-2 w-[calc(100%-2rem)] sm:w-96">

    @php
        $toasts = [];

        if (isset($errors) && $errors->any()) {
            foreach ($errors->all() as $error) {
                $toasts[] = $error;
            }
        }

        foreach (['success', 'error', 'warning', 'info'] as $type) {
            if (session($type)) {
                $toasts[] = session($type);
            }
        }
    @endphp

    <div id="toast-container" class="fixed z-50 flex flex-col gap-2
           bottom-4 left-1/2 -translate-x-1/2
           sm:left-auto sm:right-4 sm:translate-x-0
           w-[95%] sm:w-96">

        @foreach ($toasts as $message)
            <div
                class="arz-toast relative rounded-lg bg-primary text-theme-text border border-brand/20 shadow-xl px-4 py-3 text-xs flex items-center justify-between gap-3">

                <div class="arz-toast-body pr-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-brand shrink-0"></span>
                    <span>{{ $message }}</span>
                </div>

                {{-- ❌ CLOSE BUTTON --}}
                <button onclick="removeToast(this.parentElement)" class="w-6 h-6 rounded-full hover:bg-secondary text-theme-text-muted hover:text-theme-text flex items-center justify-center text-xs transition shrink-0">
                    <i class="fa-solid fa-xmark"></i>
                </button>

            </div>
        @endforeach

    </div>

</div>
<style>
    .arz-toast {
        animation: arzSlideIn 0.3s ease;
    }

    .arz-toast.hide {
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.3s ease;
    }

    @keyframes arzSlideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
<script>
    function removeToast(toast) {
        toast.classList.add('hide');
        setTimeout(() => toast.remove(), 300);
    }

    function autoHideToast(toast, delay = 4000) {
        setTimeout(() => {
            if (toast) removeToast(toast);
        }, delay);
    }

    // 🔥 INIT (Turbo + Normal)
    function initToasts() {
        document.querySelectorAll('.arz-toast').forEach(toast => {
            autoHideToast(toast);
        });
    }

    document.addEventListener("DOMContentLoaded", initToasts);
    document.addEventListener("turbo:load", initToasts);

    // 🔥 AJAX toast
    function showToast(message) {
        const container = document.getElementById('toast-container');

        const toast = document.createElement('div');
        toast.className = "arz-toast relative rounded-lg bg-primary text-theme-text border border-brand/20 shadow-xl px-4 py-3 text-xs flex items-center justify-between gap-3";

        toast.innerHTML = `
        <div class="arz-toast-body pr-4 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-brand shrink-0"></span>
            <span>${message}</span>
        </div>
        <button onclick="removeToast(this.parentElement)" class="w-6 h-6 rounded-full hover:bg-secondary text-theme-text-muted hover:text-theme-text flex items-center justify-center text-xs transition shrink-0">
            <i class="fa-solid fa-xmark"></i>
        </button>
    `;

        container.appendChild(toast);
        autoHideToast(toast);
    }
</script>