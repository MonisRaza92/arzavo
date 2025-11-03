<div class="breadcrumb mb-4 flex justify-between items-center p-4 border-rounded bg-primary border-primary">
    <div>
        <h1 class="text-3xl font-bold tracking-tight flex items-center gap-2 text-primary">
            <i class="fa-solid fa-layer-group text-xl"></i>
            Welcome Back
        </h1>
        <p class="text-sm mt-1 pl-0.5 font-medium text-secondary">
            Empower learning. Manage with ease. Lead with insight.
        </p>
        <div class="links flex flex-wrap items-center gap-1 text-sm font-medium mt-6 text-secondary">
            <a href="{{ route('home') }}" class="hover:text-primary transition-all duration-200 flex items-center gap-1">
                <i class="fas fa-home"></i> Home
            </a>
            <i class="fas fa-angle-right text-xs opacity-70"></i>
            <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-all duration-200">Admin</a>
            <i class="fas fa-angle-right text-xs opacity-70"></i>
            <span class="capitalize">{{ request()->segment(count(request()->segments())) }}</span>
        </div>
    </div>
    <!-- <img src="{{ asset("images/pngs/admin.webp") }}" alt="" class="w-20 h-20 object-contain"> -->
    <div class="right hidden md:block">
        <div class="flex items-baseline justify-end">
            <span id="clock" class="text-5xl font-bold text-right text-primary">00:00:00</span>
        </div>
        <div class="mt-2 text-right">
            <div id="date" class="text-md font-medium text-tertiary">Loading date…</div>
        </div>
        <p id="greeting-text" class="text-primary text-xl font-bold mt-1 flex items-center justify-end gap-2">
            <i class="fas fa-smile text-yellow-400"></i>
            <span>Good Morning!</span>
        </p>
    </div>
</div>
<script>
    function updateClock() {
        const now = new Date();

        // 24-hour format
        let hours = now.getHours();
        let minutes = now.getMinutes();
        let seconds = now.getSeconds();

        const pad = n => (n < 10 ? "0" + n : n);
        document.getElementById("clock").textContent = `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;

        // Date
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        document.getElementById("date").textContent = now.toLocaleDateString("en-US", options);

        // Dynamic Greeting
        let greeting = "";
        let icon = "";
        if (hours >= 5 && hours < 12) {
            greeting = "Good Morning!";
            icon = "fa-sun";
        } else if (hours >= 12 && hours < 17) {
            greeting = "Good Afternoon!";
            icon = "fa-cloud-sun";
        } else if (hours >= 17 && hours < 21) {
            greeting = "Good Evening!";
            icon = "fa-moon";
        } else {
            greeting = "Good Night!";
            icon = "fa-star";
        }

        const greetingEl = document.getElementById("greeting-text");
        greetingEl.innerHTML = `<i class="fas ${icon} text-yellow-400"></i> <span>${greeting}</span>`;
    }

    setInterval(updateClock, 1000);
    updateClock();
</script>