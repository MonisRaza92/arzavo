<!-- Notifications Container -->
<div id="toast-container" class="fixed bottom-5 right-5 z-50 space-y-2 max-w-sm w-full">

    @php
        $toasts = [];
        
        if ($errors->any()) {
            foreach ($errors->all() as $error) {
                $toasts[] = ['message' => $error, 'type' => 'error'];
            }
        }
        
        if (session('success')) {
            $toasts[] = ['message' => session('success'), 'type' => 'success'];
        }
        if (session('error')) {
            $toasts[] = ['message' => session('error'), 'type' => 'error'];
        }
        if (session('warning')) {
            $toasts[] = ['message' => session('warning'), 'type' => 'warning'];
        }
        if (session('info')) {
            $toasts[] = ['message' => session('info'), 'type' => 'info'];
        }
    @endphp

    @forelse ($toasts as $toast)
        <div class="toast flex items-center bg-{{ $toast['type'] === 'success' ? 'green' : ($toast['type'] === 'error' ? 'red' : ($toast['type'] === 'warning' ? 'yellow' : 'blue')) }}-100 text-{{ $toast['type'] === 'success' ? 'green' : ($toast['type'] === 'error' ? 'red' : ($toast['type'] === 'warning' ? 'yellow' : 'blue')) }}-700 border-l-4 border-{{ $toast['type'] === 'success' ? 'green' : ($toast['type'] === 'error' ? 'red' : ($toast['type'] === 'warning' ? 'yellow' : 'blue')) }}-500 px-4 py-3 shadow-lg mb-2 animate-slideIn">
            <div class="flex-1 leading-tight text-sm font-medium">
                {{ $toast['message'] }}
            </div>
            <button class="ml-4 text-base focus:outline-none hover:opacity-70 transition-opacity" onclick="this.parentElement.remove()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @empty
    @endforelse

</div>

<!-- Toast Animation Script -->
<script>
    function showToast(message, type = 'info') {
        const container = document.getElementById('toast-container');
        const colors = {
            success: {
                bg: 'bg-green-100',
                text: 'text-green-700',
                border: 'border-green-500'
            },
            error: {
                bg: 'bg-red-100',
                text: 'text-red-700',
                border: 'border-red-500'
            },
            warning: {
                bg: 'bg-yellow-100',
                text: 'text-yellow-700',
                border: 'border-yellow-500'
            },
            info: {
                bg: 'bg-blue-100',
                text: 'text-blue-700',
                border: 'border-blue-500'
            }
        };
        const color = colors[type] || colors.info;

        const toast = document.createElement('div');
        toast.className = `toast flex items-start ${color.bg} ${color.text} border-l-4 ${color.border} px-4 py-3 shadow-lg rounded-lg mb-2`;
        toast.innerHTML = `
            <div class="flex-1 leading-tight text-sm font-medium">${message}</div>
            <button class="ml-4 text-lg focus:outline-none" onclick="this.parentElement.remove()">
                &times;
            </button>
        `;

        container.appendChild(toast);
        autoHideToast(toast);
    }

    function autoHideToast(toast) {
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-x-5');
            setTimeout(() => toast.remove(), 500);
        }, 10000);
    }

    document.addEventListener("DOMContentLoaded", function() {
        const toasts = document.querySelectorAll('.toast');
        toasts.forEach((toast) => autoHideToast(toast));
    });
</script>
<style>
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-slideIn {
        animation: slideIn 0.3s ease-out;
    }
</style>