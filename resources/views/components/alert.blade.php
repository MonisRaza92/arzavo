<!-- Notifications Container -->
<div id="toast-container" class="fixed bottom-5 right-5 z-1000 space-y-3 w-80">

    <!-- Validation Errors (each li as a toast) -->
    @if ($errors->any())
    @foreach ($errors->all() as $error)
    <div class="toast flex justify-between items-center bg-red-200 text-red-800 border-l-8 border-red-800 px-4 py-3 shadow-md transition duration-500 ease-in-out transform">
        <span>{{ $error }}</span>
        <button class="ml-2 text-lg focus:outline-none" onclick="this.parentElement.remove()"><i class="fa-solid fa-xmark"></i></button>
    </div>
    @endforeach
    @endif

    <!-- Success -->
    @if (session('success'))
    <div class="toast flex justify-between items-center bg-green-200 text-green-800 border-l-8 border-green-800 px-4 py-3 shadow-md transition duration-500 ease-in-out transform">
        <span>{{ session('success') }}</span>
        <button class="ml-2 text-lg focus:outline-none" onclick="this.parentElement.remove()"><i class="fa-solid fa-xmark"></i></button>
    </div>
    @endif

    <!-- Info -->
    @if (session('info'))
    <div class="toast flex justify-between items-center bg-blue-200 text-blue-800 border-l-8 border-blue-800 px-4 py-3 shadow-md transition duration-500 ease-in-out transform">
        <span>{{ session('info') }}</span>
        <button class="ml-2 text-lg focus:outline-none" onclick="this.parentElement.remove()"><i class="fa-solid fa-xmark"></i></button>
    </div>
    @endif

</div>

<!-- Toast Animation Script -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toasts = document.querySelectorAll('.toast');
        toasts.forEach((toast, index) => {
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-5'); // fade + slide out
                setTimeout(() => toast.remove(), 500);
            }, 20000 + (index * 500)); // auto hide after 20s
        });
    });
</script>