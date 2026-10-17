<div class="breadcrumb mb-6 flex justify-between items-center p-6 rounded-lg bg-primary">
    <div>
        <h1 class="text-xl sm:text-2xl font-semibold tracking-tight flex items-center gap-2.5 text-theme-text">
            <i class="fa-solid fa-layer-group text-brand text-xl"></i>
            Welcome Back
        </h1>
        <p class="text-xs sm:text-sm mt-1 pl-0.5 font-normal text-theme-text-muted">
            Empower learning. Manage with ease. Lead with insight.
        </p>
        <div class="links flex flex-wrap items-center gap-1.5 text-xs font-normal mt-5 text-theme-text-muted">
            <a href="{{ route('tenant.home') }}" class="hover:text-theme-text transition flex items-center gap-1">
                <i class="fa-solid fa-house text-[11px]"></i> Home
            </a>
            <i class="fa-solid fa-chevron-right text-[9px] opacity-60"></i>
            <a href="{{ route('admin.dashboard.index') }}" class="hover:text-theme-text transition">Admin</a>
            <i class="fa-solid fa-chevron-right text-[9px] opacity-60"></i>
            <span class="text-brand font-medium capitalize">{{ request()->segment(count(request()->segments())) }}</span>
        </div>
    </div>

    <div class="right hidden md:block">
        <div class="flex items-baseline justify-end">
            <span id="clock" class="text-4xl font-semibold text-right text-theme-text font-mono tracking-tight">00:00:00</span>
        </div>
        <div class="mt-1.5 text-right">
            <div id="date" class="text-xs sm:text-sm font-normal text-theme-text-muted">Loading date…</div>
        </div>
        <p id="greeting-text" class="text-theme-text text-lg font-semibold mt-1.5 flex items-center justify-end gap-2">
            <i class="fas fa-smile text-amber-400"></i>
            <span>Good Morning!</span>
        </p>
    </div>
</div>

<script>
    (function () {
        const clockEl = document.getElementById("clock");
        const dateEl = document.getElementById("date");
        const greetingEl = document.getElementById("greeting-text");

        if (!clockEl || !dateEl || !greetingEl) return;

        function updateClock() {
            const now = new Date();

            // Time (24h)
            const pad = n => (n < 10 ? "0" + n : n);
            const hours = now.getHours();
            const minutes = now.getMinutes();
            const seconds = now.getSeconds();

            clockEl.textContent = `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;

            // Date
            dateEl.textContent = now.toLocaleDateString("en-US", {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            // Greeting
            let greeting = "";
            let icon = "";

            if (hours >= 5 && hours < 12) {
                greeting = "Good Morning!";
                icon = "fa-sun text-amber-400";
            } else if (hours >= 12 && hours < 17) {
                greeting = "Good Afternoon!";
                icon = "fa-cloud-sun text-amber-400";
            } else if (hours >= 17 && hours < 21) {
                greeting = "Good Evening!";
                icon = "fa-moon text-indigo-400";
            } else {
                greeting = "Good Night!";
                icon = "fa-star text-indigo-400";
            }

            greetingEl.innerHTML = `
                <i class="fas ${icon}"></i>
                <span>${greeting}</span>
            `;
        }

        updateClock();
        setInterval(updateClock, 1000);
    })();
</script>
