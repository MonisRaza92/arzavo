<section id="home" class="relative overflow-hidden flex flex-col justify-center items-center h-dvh w-full">
    <!-- Hero Content -->
    <div class="mx-auto max-w-[900px] px-4 sm:px-8 xl:px-0 relative z-1">
        <div class="text-center">
            <a href="index.html#"
                class="hero-subtitle-gradient hover:hero-subtitle-hover relative mb-5 font-medium text-sm inline-flex items-center gap-2 py-2 px-4.5 rounded-full">
                <img src="{{ asset('images/logo/arzavo-dark.png') }}" class="h-2.5" alt="icon">

                <span class="hero-subtitle-text">
                    Empower Every Institution!
                </span>
            </a>
            <h1 class="text-primary mb-6 text-3xl font-extrabold sm:text-5xl xl:text-heading-1">
                <span class="text-accent">Empowering</span> Every Educational Institution to <span class="text-accent-secondary">Grow</span> Smarter
            </h1>
            <p class="max-w-[800px] mx-auto mb-9 font-medium md:text-lg">
                A unified digital platform designed for schools, colleges, coaching institutes, and training centers — helping them manage students, staff, academics, fees, attendance, communication, and more. From admissions to analytics, everything becomes organized, accessible, and effortless. Modern, scalable, and customizable — built to support every institution’s journey toward smarter education.
            </p>

        </div>
    </div>
    <a href="{{ route('register.form') }}" class="w-90 text-center block bg-invert text-invert uppercase px-4 py-2 border-invert font-semibold">Register <i class="fa-solid fa-user-plus"></i></a>

    <!-- <div class="mt-17">
        <img class="mx-auto" src="{{ asset('images/arzavo/dashboard-demo.png') }}" alt="hero" />
    </div> -->
</section>