<!-- TOAST CONTAINER -->
<div id="toast-container" class="fixed bottom-4 right-4 z-50 flex flex-col gap-2 w-[calc(100%-2rem)] sm:w-96">

    @php
        $toasts = [];

        if ($errors->any()) {
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
                class="arz-toast relative border-rounded bg-black/80 text-gray-200 px-4 py-3 text-sm backdrop-blur-md flex items-center justify-between">

                <div class="arz-toast-body pr-6">
                    {{ $message }}
                </div>

                {{-- ❌ CLOSE BUTTON --}}
                <button onclick="removeToast(this.parentElement)" class="text-gray-200 hover:text-white text-sm">
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
        toast.className = "arz-toast relative border-rounded bg-black/80 text-gray-200 px-4 py-3 text-sm backdrop-blur-md flex items-center justify-between";

        toast.innerHTML = `
        <div class="arz-toast-body pr-6">${message}</div>
        <button onclick="removeToast(this.parentElement)" class="absolute top-2 right-2 text-gray-400 hover:text-white text-xs">
            <i class="fa-solid fa-xmark"></i>
        </button>
    `;

        container.appendChild(toast);
        autoHideToast(toast);
    }
</script>