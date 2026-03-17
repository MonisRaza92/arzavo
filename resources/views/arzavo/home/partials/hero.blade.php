<div class="relative h-screen w-full pb-12 pt-24">
    <video
        src="{{ media('videos/tenant/hero-video.mp4') }}"
        autoplay
        muted
        loop
        class="h-screen w-full object-cover absolute top-0 left-0"></video>

    <div class="absolute inset-0 bg-black/55"></div>

    <div class="container relative h-full flex items-end">
        <div class="content">
            <h1 class="text-5xl md:text-7xl font-bold text-white leading-[1.1] mb-6">
                <span class="text-accent-secondary">One</span> & <span class="text-accent">Only</span> Platform to <br> Run Your Entire Institute.
            </h1>

            <!-- WHAT PROBLEM IT SOLVES -->
            <p class="text-base md:text-lg text-gray-300 mb-8 leading-relaxed max-w-6xl">
                Arzavo centralizes your entire education system into one intelligent platform.
                From student admissions to daily operations, everything is managed seamlessly
                through a single dashboard — eliminating manual work, scattered tools,
                and operational confusion.
            </p>

            <div class="email flex border-primary rounded-full w-full md:w-3xl relative mb-8">
                <input type="email" placeholder="Enter your email" class="border-none w-full outline-none p-4 text-invert placeholder:text-gray-400 pl-8">
                <x-button class="bg-white! text-black! px-4 py-2 rounded-full! border-0! absolute right-2 top-1/2 transform -translate-y-1/2">Get Started</x-button>
            </div>

            <!-- FEATURES SNAPSHOT -->
            <div class="flex flex-wrap gap-x-8 gap-y-4 text-sm md:text-base text-gray-200">

                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-user-graduate text-invert"></i>
                    Student & Teacher Management
                </span>

                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-money-bill-wave text-invert"></i>
                    Fees, Salaries & Accounts
                </span>

                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-book-open text-invert"></i>
                    Courses, Batches & Attendance
                </span>

                <!-- <span class="flex items-center gap-2">
                    <i class="fa-solid fa-chart-line text-invert"></i>
                    Reports, Analytics & Automation
                </span> -->

            </div>
        </div>
    </div>
</div>